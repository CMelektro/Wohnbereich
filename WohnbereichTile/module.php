<?php
require_once __DIR__ . '/../libs/Widget.php';
require_once __DIR__ . '/../libs/OtherRooms.php';
class Wohnbereich extends IPSModule
{
    private const AREAS = ['Kitchen' => ['kitchen', 'Küche'], 'Dining' => ['dining', 'Esszimmer'], 'Living' => ['living', 'Wohnzimmer']];

    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString('Title', 'Wohnbereich');
        $this->RegisterPropertyBoolean('ShowTitle', true);
        $this->RegisterPropertyBoolean('ShowSummary', true);
        $this->RegisterPropertyInteger('BackgroundColor', 2105636);
        $this->RegisterPropertyInteger('TextColor', 16777215);
        foreach (self::AREAS as $area => [$room, $name]) {
            $this->RegisterPropertyString($area . 'Name', $name);
            $this->RegisterPropertyBoolean($area . 'Enabled', true);
            foreach (range(1, 3) as $i) {
                $p = $area . 'Light' . $i;
                $this->RegisterPropertyString($p . 'Name', 'Lichtkreis ' . $i);
                $this->RegisterPropertyString($p . 'Type', ['pendant', 'spots', 'led'][$i - 1]);
                foreach (['Enabled' => false, 'UseSwitch' => true, 'UseDim' => true, 'AutoPosition' => true] as $key => $value) $this->RegisterPropertyBoolean($p . $key, $value);
                foreach (['Switch', 'SwitchStatus', 'Dim', 'DimStatus', 'X', 'Y'] as $key) $this->RegisterPropertyInteger($p . $key, 0);
                $this->RegisterPropertyInteger($p . 'Maximum', 100);
                $this->RegisterPropertyInteger($p . 'Scale', 100);
            }
            foreach (range(1, 2) as $i) {
                $p = $area . 'Socket' . $i;
                $this->RegisterPropertyBoolean($p . 'Enabled', false);
                $this->RegisterPropertyString($p . 'Name', 'Steckdosen ' . $i);
                $this->RegisterPropertyInteger($p . 'Control', 0);
                $this->RegisterPropertyInteger($p . 'Status', 0);
                $p = $area . 'Blind' . $i;
                $this->RegisterPropertyString($p . 'Name', 'Jalousie ' . $i);
                foreach (['Enabled' => false, 'UseMove' => true, 'UseStop' => true, 'UsePosition' => true, 'DownValue' => true, 'StopValue' => true, 'Invert' => false] as $key => $value) $this->RegisterPropertyBoolean($p . $key, $value);
                $this->RegisterPropertyString($p . 'CommandMode', 'separate');
                foreach (['Move', 'Stop', 'Position', 'Feedback'] as $key) $this->RegisterPropertyInteger($p . $key, 0);
                foreach (['UpCommand' => 0, 'StopCommand' => 1, 'DownCommand' => 2, 'Maximum' => 100] as $key => $value) $this->RegisterPropertyInteger($p . $key, $value);
            }
        }
        $this->RegisterPropertyBoolean('ClimateEnabled', false);
        $this->RegisterPropertyString('ClimateName', 'Raumklima');
        foreach (['Actual', 'Target', 'Feedback'] as $key) $this->RegisterPropertyInteger('Climate' . $key, 0);
        $this->RegisterPropertyInteger('ClimateMin', 5);
        $this->RegisterPropertyInteger('ClimateMax', 30);
        $this->RegisterPropertyBoolean('OtherRoomsEnabled', true);
        $this->RegisterPropertyString('OtherRoomsTitle', 'Weitere Räume');
        $this->RegisterPropertyString('OtherRooms', '[]');
        $this->RegisterTimer('RefreshRooms', 5000, 'WBR_RefreshRooms($_IPS[\'TARGET\']);');
        $this->SetVisualizationType(WohnbereichWidget::VisualizationType());
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetVisualizationType(WohnbereichWidget::VisualizationType());
        foreach ($this->GetReferenceList() as $id) $this->UnregisterReference($id);
        foreach ($this->GetMessageList() as $id => $messages) foreach ($messages as $message) $this->UnregisterMessage($id, $message);
        foreach ($this->GetWatchVariables() as $id) {
            $this->RegisterReference($id);
            $this->RegisterMessage($id, VM_UPDATE);
        }
        $this->SetBuffer('RoomsWatchKey', '');
        $this->SetBuffer('RoomsStateHash', '');
        $this->RefreshRooms();
    }

    private function RoomSources(): array
    {
        return WohnbereichOtherRooms::Sources($this->ReadPropertyString('OtherRooms'), $this->ReadPropertyBoolean('OtherRoomsEnabled'));
    }

    public function RefreshRooms(): void
    {
        $sources = $this->RoomSources();
        $ids = $this->GetWatchVariables();
        $key = json_encode([$sources, $ids]);
        if ($key !== $this->GetBuffer('RoomsWatchKey')) {
            foreach ($this->GetReferenceList() as $id) $this->UnregisterReference($id);
            foreach ($this->GetMessageList() as $id => $messages) foreach ($messages as $message) $this->UnregisterMessage($id, $message);
            foreach ($sources as $source) if (WohnbereichOtherRooms::Valid($source['instance'])) $this->RegisterReference($source['instance']);
            foreach ($ids as $id) { $this->RegisterReference($id); $this->RegisterMessage($id, VM_UPDATE); }
            $this->SetBuffer('RoomsWatchKey', $key);
        }
        $state = $this->GetState();
        $hash = hash('sha256', $state);
        if ($hash !== $this->GetBuffer('RoomsStateHash')) { $this->SetBuffer('RoomsStateHash', $hash); $this->UpdateVisualizationValue($state); }
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        if ($Message === VM_UPDATE) $this->RefreshRooms();
    }

    public function GetVisualizationTile(): string { return WohnbereichWidget::HTML($this->GetState()); }

    public function GetWatchVariables(): array
    {
        $properties = ['ClimateActual', 'ClimateTarget', 'ClimateFeedback'];
        foreach (self::AREAS as $area => $unused) {
            foreach (range(1, 3) as $i) foreach (['Switch', 'SwitchStatus', 'Dim', 'DimStatus'] as $suffix) $properties[] = $area . 'Light' . $i . $suffix;
            foreach (range(1, 2) as $i) {
                foreach (['Control', 'Status'] as $suffix) $properties[] = $area . 'Socket' . $i . $suffix;
                foreach (['Move', 'Stop', 'Position', 'Feedback'] as $suffix) $properties[] = $area . 'Blind' . $i . $suffix;
            }
        }
        $ids = [];
        foreach ($properties as $property) { $id = $this->ReadPropertyInteger($property); if (IPS_VariableExists($id)) $ids[] = $id; }
        return array_values(array_unique(array_merge($ids, WohnbereichOtherRooms::Watch($this->RoomSources()))));
    }

    private function Valid(string $property, array $types): bool
    {
        $id = $this->ReadPropertyInteger($property);
        return IPS_VariableExists($id) && in_array(IPS_GetVariable($id)['VariableType'], $types, true);
    }
    private function Writable(string $property, array $types): bool
    {
        if (!$this->Valid($property, $types)) return false;
        $v = IPS_GetVariable($this->ReadPropertyInteger($property));
        return empty($v['VariableIsLocked']) && ($v['VariableCustomAction'] ?: $v['VariableAction']) > 1;
    }
    private function Read(string $feedback, string $command, array $types)
    {
        foreach ([$feedback, $command] as $p) if ($this->Valid($p, $types)) {
            $value = GetValue($this->ReadPropertyInteger($p));
            return is_float($value) && !is_finite($value) ? null : $value;
        }
        return null;
    }
    private function CanPercent(string $p, int $maximum): bool
    {
        return in_array($maximum, [1, 100, 255], true) && $this->Writable($p, $maximum === 1 ? [2] : [1, 2]);
    }
    private function Percent($value, int $maximum, bool $invert = false)
    {
        if ($value === null || !in_array($maximum, [1, 100, 255], true)) return null;
        $value = max(0, min(100, (float)$value * 100 / $maximum));
        return $invert ? 100 - $value : $value;
    }
    private function BlindCan(string $p, string $action): bool
    {
        if ($this->ReadPropertyString($p . 'CommandMode') === 'combined') {
            $codes = [$this->ReadPropertyInteger($p . 'UpCommand'), $this->ReadPropertyInteger($p . 'StopCommand'), $this->ReadPropertyInteger($p . 'DownCommand')];
            return count(array_unique($codes)) === 3 && $this->Writable($p . 'Move', [1]);
        }
        if ($this->ReadPropertyString($p . 'CommandMode') !== 'separate') return false;
        if ($action === 'Stop') return $this->ReadPropertyInteger($p . 'Stop') !== $this->ReadPropertyInteger($p . 'Move') && $this->Writable($p . 'Stop', [0]);
        return $this->Writable($p . 'Move', [0]);
    }

    public function GetState(): string
    {
        $result = ['mode' => 'main', 'connected' => true, 'title' => $this->ReadPropertyString('Title'), 'showTitle' => $this->ReadPropertyBoolean('ShowTitle'), 'showSummary' => $this->ReadPropertyBoolean('ShowSummary'),
            'backgroundColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('BackgroundColor') & 0xFFFFFF), 'textColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('TextColor') & 0xFFFFFF), 'areas' => []];
        foreach (self::AREAS as $area => [$room, $name]) {
            $a = ['id' => $area, 'room' => $room, 'name' => $this->ReadPropertyString($area . 'Name'), 'enabled' => $this->ReadPropertyBoolean($area . 'Enabled'), 'lights' => [], 'sockets' => [], 'blinds' => []];
            foreach (range(1, 3) as $i) {
                $p = $area . 'Light' . $i; $switch = $this->ReadPropertyBoolean($p . 'UseSwitch'); $dim = $this->ReadPropertyBoolean($p . 'UseDim');
                $level = $this->Percent($this->Read($p . 'DimStatus', $p . 'Dim', [1,2]), $this->ReadPropertyInteger($p . 'Maximum'));
                $on = $this->Read($p . 'SwitchStatus', $p . 'Switch', [0]);
                if ($on === null && !$switch) $on = $level === null ? null : $level > 0;
                $a['lights'][] = ['id' => 'L' . $i, 'ident' => $p, 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'), 'name' => $this->ReadPropertyString($p . 'Name'), 'type' => $this->ReadPropertyString($p . 'Type'), 'useSwitch' => $switch, 'useDim' => $dim,
                    'on' => $on, 'level' => $level, 'canSwitch' => $on !== null && $this->Writable($p . 'Switch', [0]), 'canDim' => $this->CanPercent($p . 'Dim', $this->ReadPropertyInteger($p . 'Maximum')),
                    'autoPosition' => $this->ReadPropertyBoolean($p . 'AutoPosition'), 'x' => $this->ReadPropertyInteger($p . 'X'), 'y' => $this->ReadPropertyInteger($p . 'Y'), 'scale' => $this->ReadPropertyInteger($p . 'Scale')];
            }
            foreach (range(1, 2) as $i) {
                $p = $area . 'Socket' . $i; $on = $this->Read($p . 'Status', $p . 'Control', [0]);
                $a['sockets'][] = ['id' => 'S' . $i, 'ident' => $p, 'name' => $this->ReadPropertyString($p . 'Name'), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'), 'on' => $on, 'canSwitch' => $on !== null && $this->Writable($p . 'Control', [0])];
                $p = $area . 'Blind' . $i;
                $a['blinds'][] = ['id' => 'B' . $i, 'ident' => $p, 'name' => $this->ReadPropertyString($p . 'Name'), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'), 'useMove' => $this->ReadPropertyBoolean($p . 'UseMove'), 'useStop' => $this->ReadPropertyBoolean($p . 'UseStop'), 'usePosition' => $this->ReadPropertyBoolean($p . 'UsePosition'),
                    'canMove' => $this->BlindCan($p, 'Move'), 'canStop' => $this->BlindCan($p, 'Stop'), 'canPosition' => $this->CanPercent($p . 'Position', $this->ReadPropertyInteger($p . 'Maximum')),
                    'position' => $this->Percent($this->Read($p . 'Feedback', $p . 'Position', [1,2]), $this->ReadPropertyInteger($p . 'Maximum'), $this->ReadPropertyBoolean($p . 'Invert'))];
            }
            $result['areas'][] = $a;
        }
        $result['climate'] = ['enabled' => $this->ReadPropertyBoolean('ClimateEnabled'), 'name' => $this->ReadPropertyString('ClimateName'), 'actual' => $this->Read('ClimateActual', 'ClimateActual', [1,2]), 'target' => $this->Read('ClimateFeedback', 'ClimateTarget', [1,2]), 'min' => $this->ReadPropertyInteger('ClimateMin'), 'max' => $this->ReadPropertyInteger('ClimateMax'), 'step' => $this->Valid('ClimateTarget', [1]) ? 1 : 0.5,
            'canSet' => $this->Writable('ClimateTarget', [1,2]) && $this->ReadPropertyInteger('ClimateMin') < $this->ReadPropertyInteger('ClimateMax')];
        $result['otherRoomsTitle'] = $this->ReadPropertyString('OtherRoomsTitle');
        $result['otherRooms'] = array_map([WohnbereichOtherRooms::class, 'Snapshot'], $this->RoomSources());
        return json_encode($result, JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }

    private function Write(string $p, $value, array $types): void
    {
        if (!$this->Writable($p, $types)) throw new RuntimeException('Variable oder Aktion fehlt: ' . $p);
        $id = $this->ReadPropertyInteger($p); $type = IPS_GetVariable($id)['VariableType'];
        $value = $type === 0 ? (bool)$value : ($type === 1 ? (int)round($value) : (float)$value);
        if (RequestAction($id, $value) === false) throw new RuntimeException('Befehl fehlgeschlagen: ' . $p);
    }
    private function WritePercent(string $p, $value, int $maximum, bool $invert = false): void
    {
        if (!is_numeric($value) || !is_finite((float)$value) || $value < 0 || $value > 100 || !$this->CanPercent($p, $maximum)) throw new InvalidArgumentException('Ungültiger Prozentwert oder Wertebereich.');
        $this->Write($p, ($invert ? 100 - (float)$value : (float)$value) * $maximum / 100, [1,2]);
    }
    public function RequestAction($Ident, $Value)
    {
        try { $this->Execute($Ident, $Value); }
        catch (Throwable $error) {
            $this->UpdateVisualizationValue(json_encode(['error' => true, 'message' => $error->getMessage()], JSON_INVALID_UTF8_SUBSTITUTE));
            throw $error;
        }
    }
    private function Execute($ident, $value): void
    {
        if (is_string($ident) && strpos($ident, 'Other') === 0) {
            WohnbereichOtherRooms::Relay($this->RoomSources(), $ident, $value);
            $this->RefreshRooms();
            return;
        }
        if ($ident === 'ClimateTarget') {
            if (!$this->ReadPropertyBoolean('ClimateEnabled')) return;
            $min = $this->ReadPropertyInteger('ClimateMin'); $max = $this->ReadPropertyInteger('ClimateMax');
            if (!is_numeric($value) || !is_finite((float)$value) || $min >= $max || $value < $min || $value > $max) throw new InvalidArgumentException('Solltemperatur außerhalb der Grenzen.');
            if ($this->Valid('ClimateTarget', [1]) && floor((float)$value) !== (float)$value) throw new InvalidArgumentException('Solltemperatur benötigt ganze Grad.');
            $this->Write('ClimateTarget', $value, [1,2]);
        } elseif (preg_match('/^(Kitchen|Dining|Living)(Light[1-3]|Socket[12]|Blind[12])(Switch|Dim|Up|Down|Stop|Position)$/D', $ident, $m)) {
            $area = $m[1]; $device = $m[2]; $action = $m[3]; $p = $area . $device;
            if (!$this->ReadPropertyBoolean($area . 'Enabled') || !$this->ReadPropertyBoolean($p . 'Enabled')) return;
            if (strpos($device, 'Light') === 0) {
                if ($action === 'Switch' && $this->ReadPropertyBoolean($p . 'UseSwitch')) {
                    $on = $this->Read($p . 'SwitchStatus', $p . 'Switch', [0]);
                    if ($on === null) throw new RuntimeException('Schaltzustand unbekannt.');
                    $this->Write($p . 'Switch', !$on, [0]);
                } elseif ($action === 'Dim' && $this->ReadPropertyBoolean($p . 'UseDim')) $this->WritePercent($p . 'Dim', $value, $this->ReadPropertyInteger($p . 'Maximum'));
                elseif (!in_array($action, ['Switch','Dim'], true)) throw new InvalidArgumentException('Ungültiger Lichtbefehl.');
            } elseif (strpos($device, 'Socket') === 0) {
                if ($action !== 'Switch') throw new InvalidArgumentException('Ungültiger Steckdosenbefehl.');
                $on = $this->Read($p . 'Status', $p . 'Control', [0]);
                if ($on === null) throw new RuntimeException('Schaltzustand unbekannt.');
                $this->Write($p . 'Control', !$on, [0]);
            } else {
                if ($action === 'Position') { if ($this->ReadPropertyBoolean($p . 'UsePosition')) $this->WritePercent($p . 'Position', $value, $this->ReadPropertyInteger($p . 'Maximum'), $this->ReadPropertyBoolean($p . 'Invert')); }
                elseif (in_array($action, ['Up', 'Down', 'Stop'], true)) {
                    if (!$this->ReadPropertyBoolean($p . ($action === 'Stop' ? 'UseStop' : 'UseMove'))) return;
                    if (!$this->BlindCan($p, $action)) throw new RuntimeException('Fahr-/Stoppzuordnung prüfen. Getrennte KNX-Objekte benötigen verschiedene Variablen; kombinierte Befehle drei verschiedene Werte.');
                    if ($this->ReadPropertyString($p . 'CommandMode') === 'combined') $this->Write($p . 'Move', $this->ReadPropertyInteger($p . $action . 'Command'), [1]);
                    else $this->Write($p . ($action === 'Stop' ? 'Stop' : 'Move'), $action === 'Stop' ? $this->ReadPropertyBoolean($p . 'StopValue') : ($action === 'Down' ? $this->ReadPropertyBoolean($p . 'DownValue') : !$this->ReadPropertyBoolean($p . 'DownValue')), [0]);
                } else throw new InvalidArgumentException('Ungültiger Jalousiebefehl.');
            }
        } else throw new InvalidArgumentException('Unbekannter Bedienbefehl.');
        $this->UpdateVisualizationValue($this->GetState());
    }
}
