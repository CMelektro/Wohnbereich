<?php
/** Read-only adapter for existing CMelektro RoomTile configuration. Commands stay in the source module. */
final class WohnbereichOtherRooms
{
    public const MODULE = '{95207FE5-8A45-4275-9F0A-0A4965D8CD58}';
    public static function Sources(string $json, bool $enabled): array
    {
        if (!$enabled) return [];
        $rows = json_decode($json, true);
        if (!is_array($rows)) return [];
        $result = [];
        foreach ($rows as $row) {
            if (!is_array($row) || empty($row['Enabled'])) continue;
            $id = (int)($row['Instance'] ?? 0);
            if (!$id || isset($result[$id])) continue;
            $result[$id] = ['instance' => $id, 'label' => trim((string)($row['Name'] ?? ''))];
            if (count($result) >= 24) break;
        }
        return array_values($result);
    }
    public static function Valid(int $id): bool
    {
        return IPS_InstanceExists($id) && IPS_GetInstance($id)['ModuleInfo']['ModuleID'] === self::MODULE;
    }
    public static function Config(int $id): array
    {
        if (!self::Valid($id)) return [];
        $config = json_decode(IPS_GetConfiguration($id), true);
        return is_array($config) ? $config : [];
    }
    private static function Variable(array $p, string $key, array $types): int
    {
        $id = (int)($p[$key] ?? 0);
        return IPS_VariableExists($id) && in_array(IPS_GetVariable($id)['VariableType'], $types, true) ? $id : 0;
    }
    private static function Can(array $p, string $key, array $types): bool
    {
        $id = self::Variable($p, $key, $types);
        if (!$id) return false;
        $v = IPS_GetVariable($id);
        return empty($v['VariableIsLocked']) && ($v['VariableCustomAction'] ?: $v['VariableAction']) > 1;
    }
    private static function Read(array $p, string $feedback, string $command, array $types)
    {
        foreach ([$feedback, $command] as $key) {
            $id = self::Variable($p, $key, $types);
            if ($id) { $v = GetValue($id); return is_float($v) && !is_finite($v) ? null : $v; }
        }
        return null;
    }
    private static function CanPercent(array $p, string $key, int $max): bool
    {
        return in_array($max, [1,100,255], true) && self::Can($p, $key, $max === 1 ? [2] : [1,2]);
    }
    private static function Percent($value, int $max, bool $invert = false)
    {
        if ($value === null || !in_array($max, [1,100,255], true)) return null;
        $value = max(0, min(100, (float)$value * 100 / $max));
        return $invert ? 100 - $value : $value;
    }
    public static function Watch(array $sources): array
    {
        $ids = [];
        foreach ($sources as $source) {
            $p = self::Config($source['instance']);
            foreach ($p as $key => $value) {
                // Only explicit variable properties; never interpret numeric layout/settings as IDs.
                if (!preg_match('/^(Climate(Actual|Target|Feedback)|(Pendant|Spot|Led|Light4)(Switch|SwitchStatus|Control|Status|DimControl|DimStatus)|Socket2?(Control|Status)|Blind[12](Move|Stop|Position|Feedback))$/D', $key)) continue;
                $id = (int)$value; if (IPS_VariableExists($id)) $ids[] = $id;
            }
        }
        return array_values(array_unique($ids));
    }
    public static function Snapshot(array $source): array
    {
        $id = $source['instance']; $p = self::Config($id);
        $name = $source['label'] ?: (trim((string)($p['Title'] ?? '')) ?: (IPS_ObjectExists($id) ? IPS_GetName($id) : 'Raum nicht verfügbar'));
        $r = ['id'=>'Other'.$id, 'instance'=>$id, 'name'=>$name, 'enabled'=>true, 'connected'=>!empty($p), 'lights'=>[], 'sockets'=>[], 'blinds'=>[], 'climate'=>['enabled'=>false]];
        if (!$p) { $r['message']='Verknüpfte Raumkachel ist nicht verfügbar. Zuordnung prüfen.'; return $r; }
        $rows = [['Pendant','PendantSwitch','PendantSwitchStatus','PendantControl','PendantStatus'],['Spot','SpotSwitch','SpotSwitchStatus','SpotControl','SpotStatus'],['Led','LedControl','LedStatus','LedDimControl','LedDimStatus'],['Light4','Light4Switch','Light4SwitchStatus','Light4Control','Light4Status']];
        foreach ($rows as $i => [$prefix,$sw,$sf,$dim,$df]) {
            $useSwitch=!empty($p[$prefix.'UseSwitch']); $useDim=!empty($p[$prefix.'UseDim']); $max=(int)($p[$prefix.'Maximum']??100);
            $on=self::Read($p,$sf,$sw,[0]); $level=self::Percent(self::Read($p,$df,$dim,[1,2]),$max);
            if ($on===null&&!$useSwitch) $on=$level===null?null:$level>0;
            $r['lights'][]=['id'=>'L'.($i+1),'ident'=>'Other'.$id.'_L'.($i+1),'name'=>(string)($p[$prefix.'Name']??'Lichtkreis '.($i+1)),'type'=>(string)($p[$prefix.'Type']??'spots'),'enabled'=>!empty($p[$prefix.'Enabled']),'useSwitch'=>$useSwitch,'useDim'=>$useDim,'on'=>$on,'level'=>$level,'canSwitch'=>$on!==null&&self::Can($p,$sw,[0]),'canDim'=>self::CanPercent($p,$dim,$max)];
        }
        foreach (['Socket','Socket2'] as $i=>$prefix) {
            $on=self::Read($p,$prefix.'Status',$prefix.'Control',[0]);
            $r['sockets'][]=['id'=>'S'.($i+1),'ident'=>'Other'.$id.'_S'.($i+1),'name'=>(string)($p[$prefix.'Name']??'Steckdosen '.($i+1)),'enabled'=>!empty($p[$i?'Socket2Enabled':'ShowSocket'])&&self::Variable($p,$prefix.'Control',[0])>0,'on'=>$on,'canSwitch'=>$on!==null&&self::Can($p,$prefix.'Control',[0])];
            $prefix='Blind'.($i+1); $max=(int)($p[$prefix.'Maximum']??100);
            $r['blinds'][]=['id'=>'B'.($i+1),'ident'=>'Other'.$id.'_B'.($i+1),'name'=>(string)($p[$prefix.'Name']??'Jalousie '.($i+1)),'enabled'=>!empty($p[$prefix.'Enabled']),'useMove'=>!empty($p[$prefix.'UseMove']),'useStop'=>!empty($p[$prefix.'UseStop']),'usePosition'=>!empty($p[$prefix.'UsePosition']),'canMove'=>self::Can($p,$prefix.'Move',[0]),'canStop'=>(int)($p[$prefix.'Move']??0)!==(int)($p[$prefix.'Stop']??0)&&self::Can($p,$prefix.'Stop',[0]),'canPosition'=>self::CanPercent($p,$prefix.'Position',$max),'position'=>self::Percent(self::Read($p,$prefix.'Feedback',$prefix.'Position',[1,2]),$max,!empty($p[$prefix.'Invert']))];
        }
        $min=(int)($p['ClimateMin']??5); $max=(int)($p['ClimateMax']??30);
        $r['climate']=['enabled'=>!empty($p['ClimateEnabled']),'name'=>(string)($p['ClimateName']??'Raumklima'),'actual'=>self::Read($p,'ClimateActual','ClimateActual',[1,2]),'target'=>self::Read($p,'ClimateFeedback','ClimateTarget',[1,2]),'min'=>$min,'max'=>$max,'step'=>self::Variable($p,'ClimateTarget',[1])?1:0.5,'canSet'=>$min<$max&&self::Can($p,'ClimateTarget',[1,2]),'ident'=>'Other'.$id.'_Climate'];
        return $r;
    }
    public static function Relay(array $sources, string $ident, $value): void
    {
        if (!preg_match('/^Other([1-9][0-9]*)_(L[1-4](?:Switch|Dim)|S[12]Switch|B[12](?:Up|Down|Stop|Position)|ClimateTarget)$/D',$ident,$m)) throw new InvalidArgumentException('Unbekannter Raumbefehl.');
        $id=(int)$m[1]; $source=null;
        foreach ($sources as $item) if ($item['instance']===$id) $source=$item;
        if (!$source||!self::Valid($id)) throw new RuntimeException('Raum ist nicht für diese Tabletseite freigegeben.');
        $state=self::Snapshot($source); $action=$m[2]; $allowed=false;
        foreach (array_merge($state['lights'],$state['sockets'],$state['blinds']) as $d) {
            if (empty($d['enabled'])) continue;
            foreach (['Switch','Dim','Up','Down','Stop','Position'] as $suffix) {
                if ($d['id'].$suffix!==$action) continue;
                $flag=['Switch'=>'canSwitch','Dim'=>'canDim','Up'=>'canMove','Down'=>'canMove','Stop'=>'canStop','Position'=>'canPosition'][$suffix];
                $use=['Switch'=>isset($d['useSwitch'])?$d['useSwitch']:true,'Dim'=>$d['useDim']??false,'Up'=>$d['useMove']??false,'Down'=>$d['useMove']??false,'Stop'=>$d['useStop']??false,'Position'=>$d['usePosition']??false][$suffix];
                $allowed=!empty($d[$flag])&&$use;
                if (in_array($suffix,['Dim','Position'],true)&&(!is_numeric($value)||!is_finite((float)$value)||$value<0||$value>100)) throw new InvalidArgumentException('Ungültiger Prozentwert.');
            }
        }
        if ($action==='ClimateTarget') {
            $c=$state['climate']; $allowed=$c['enabled']&&$c['canSet'];
            if (!is_numeric($value)||!is_finite((float)$value)||$value<$c['min']||$value>$c['max']||($c['step']===1&&floor((float)$value)!==(float)$value)) throw new InvalidArgumentException('Ungültige Solltemperatur.');
        }
        if (!$allowed) throw new RuntimeException('Funktion ist deaktiviert oder nicht bedienbar.');
        if (IPS_RequestAction($id,$action,$value)===false) throw new RuntimeException('Raumbefehl fehlgeschlagen.');
    }
}
