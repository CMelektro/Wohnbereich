<?php
class Raumkachel extends IPSModule
{
    private const LIGHTS = [
        ['Pendant', 'PendantSwitch', 'PendantSwitchStatus', 'PendantControl', 'PendantStatus'],
        ['Spot', 'SpotSwitch', 'SpotSwitchStatus', 'SpotControl', 'SpotStatus'],
        ['Led', 'LedControl', 'LedStatus', 'LedDimControl', 'LedDimStatus'],
        ['Light4', 'Light4Switch', 'Light4SwitchStatus', 'Light4Control', 'Light4Status']
    ];

    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString('Title', 'Küche');
        $this->RegisterPropertyBoolean('ClimateEnabled', false);
        $this->RegisterPropertyString('ClimateName', 'Raumklima');
        $this->RegisterPropertyInteger('ClimateActual', 0);
        $this->RegisterPropertyInteger('ClimateTarget', 0);
        $this->RegisterPropertyInteger('ClimateFeedback', 0);
        $this->RegisterPropertyInteger('ClimateMin', 5);
        $this->RegisterPropertyInteger('ClimateMax', 30);
        $this->RegisterPropertyBoolean('ShowTitle', true);
        $this->RegisterPropertyBoolean('ShowSummary', true);
        $this->RegisterPropertyString('RoomStyle', 'kitchen');
        $this->RegisterPropertyInteger('BackgroundColor', 2105636);
        $this->RegisterPropertyInteger('TextColor', 16777215);
        $this->RegisterPropertyString('PendantName', 'Hängelampen');
        $this->RegisterPropertyBoolean('PendantEnabled', true);
        $this->RegisterPropertyBoolean('PendantUseSwitch', true);
        $this->RegisterPropertyBoolean('PendantUseDim', true);
        $this->RegisterPropertyString('PendantType', 'pendant');
        $this->RegisterPropertyBoolean('PendantAutoPosition', true);
        $this->RegisterPropertyInteger('PendantX', 460);
        $this->RegisterPropertyInteger('PendantY', 85);
        $this->RegisterPropertyInteger('PendantScale', 100);
        $this->RegisterPropertyInteger('PendantMaximum', 100);
        $this->RegisterPropertyInteger('PendantSwitch', 0);
        $this->RegisterPropertyInteger('PendantSwitchStatus', 0);
        $this->RegisterPropertyInteger('PendantControl', 0);
        $this->RegisterPropertyInteger('PendantStatus', 0);
        $this->RegisterPropertyString('SpotName', 'Deckenspots');
        $this->RegisterPropertyBoolean('SpotEnabled', true);
        $this->RegisterPropertyBoolean('SpotUseSwitch', true);
        $this->RegisterPropertyBoolean('SpotUseDim', true);
        $this->RegisterPropertyString('SpotType', 'spots');
        $this->RegisterPropertyBoolean('SpotAutoPosition', true);
        $this->RegisterPropertyInteger('SpotX', 740);
        $this->RegisterPropertyInteger('SpotY', 65);
        $this->RegisterPropertyInteger('SpotScale', 100);
        $this->RegisterPropertyInteger('SpotMaximum', 100);
        $this->RegisterPropertyInteger('SpotSwitch', 0);
        $this->RegisterPropertyInteger('SpotSwitchStatus', 0);
        $this->RegisterPropertyInteger('SpotControl', 0);
        $this->RegisterPropertyInteger('SpotStatus', 0);
        $this->RegisterPropertyString('LedName', 'LED Leiste');
        $this->RegisterPropertyBoolean('LedEnabled', true);
        $this->RegisterPropertyBoolean('LedUseSwitch', true);
        $this->RegisterPropertyBoolean('LedUseDim', false);
        $this->RegisterPropertyString('LedType', 'led');
        $this->RegisterPropertyBoolean('LedAutoPosition', true);
        $this->RegisterPropertyInteger('LedX', 775);
        $this->RegisterPropertyInteger('LedY', 305);
        $this->RegisterPropertyInteger('LedScale', 100);
        $this->RegisterPropertyInteger('LedMaximum', 100);
        $this->RegisterPropertyInteger('LedControl', 0);
        $this->RegisterPropertyInteger('LedStatus', 0);
        $this->RegisterPropertyInteger('LedDimControl', 0);
        $this->RegisterPropertyInteger('LedDimStatus', 0);
        $this->RegisterPropertyString('Light4Name', 'Stehlampe');
        $this->RegisterPropertyBoolean('Light4Enabled', false);
        $this->RegisterPropertyBoolean('Light4UseSwitch', true);
        $this->RegisterPropertyBoolean('Light4UseDim', true);
        $this->RegisterPropertyString('Light4Type', 'floor');
        $this->RegisterPropertyBoolean('Light4AutoPosition', true);
        $this->RegisterPropertyInteger('Light4X', 115);
        $this->RegisterPropertyInteger('Light4Y', 285);
        $this->RegisterPropertyInteger('Light4Scale', 100);
        $this->RegisterPropertyInteger('Light4Maximum', 100);
        $this->RegisterPropertyInteger('Light4Switch', 0);
        $this->RegisterPropertyInteger('Light4SwitchStatus', 0);
        $this->RegisterPropertyInteger('Light4Control', 0);
        $this->RegisterPropertyInteger('Light4Status', 0);
        $this->RegisterPropertyBoolean('ShowSocket', true);
        $this->RegisterPropertyString('SocketName', 'Steckdosen');
        $this->RegisterPropertyInteger('SocketControl', 0);
        $this->RegisterPropertyInteger('SocketStatus', 0);
        $this->RegisterPropertyBoolean('Socket2Enabled', false);
        $this->RegisterPropertyString('Socket2Name', 'Steckdosen 2');
        $this->RegisterPropertyInteger('Socket2Control', 0);
        $this->RegisterPropertyInteger('Socket2Status', 0);
        $this->RegisterPropertyBoolean('Blind1Enabled', false);
        $this->RegisterPropertyString('Blind1Name', 'Rollladen 1');
        $this->RegisterPropertyBoolean('Blind1UseMove', true);
        $this->RegisterPropertyBoolean('Blind1UseStop', true);
        $this->RegisterPropertyBoolean('Blind1UsePosition', true);
        $this->RegisterPropertyBoolean('Blind1DownValue', true);
        $this->RegisterPropertyBoolean('Blind1StopValue', true);
        $this->RegisterPropertyBoolean('Blind1Invert', false);
        $this->RegisterPropertyInteger('Blind1Maximum', 100);
        $this->RegisterPropertyInteger('Blind1Move', 0);
        $this->RegisterPropertyInteger('Blind1Stop', 0);
        $this->RegisterPropertyInteger('Blind1Position', 0);
        $this->RegisterPropertyInteger('Blind1Feedback', 0);
        $this->RegisterPropertyBoolean('Blind2Enabled', false);
        $this->RegisterPropertyString('Blind2Name', 'Rollladen 2');
        $this->RegisterPropertyBoolean('Blind2UseMove', true);
        $this->RegisterPropertyBoolean('Blind2UseStop', true);
        $this->RegisterPropertyBoolean('Blind2UsePosition', true);
        $this->RegisterPropertyBoolean('Blind2DownValue', true);
        $this->RegisterPropertyBoolean('Blind2StopValue', true);
        $this->RegisterPropertyBoolean('Blind2Invert', false);
        $this->RegisterPropertyInteger('Blind2Maximum', 100);
        $this->RegisterPropertyInteger('Blind2Move', 0);
        $this->RegisterPropertyInteger('Blind2Stop', 0);
        $this->RegisterPropertyInteger('Blind2Position', 0);
        $this->RegisterPropertyInteger('Blind2Feedback', 0);
        // Symcon 9.0 supports the HTML-SDK with visualization type 1.
        // Type 2 is available only from Symcon 9.1 onward.
        $this->RegisterPropertyString('PendantGarageZone', 'gate');
        $this->RegisterPropertyString('SpotGarageZone', 'kitchen');
        $this->RegisterPropertyString('LedGarageZone', 'kitchen');
        $this->RegisterPropertyString('Light4GarageZone', 'gate');
        $this->SetVisualizationType(defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')
            ? constant('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') : 1);
    }

    public function GetConfigurationForm()
    {
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);
        $fields = ['PendantGarageZone', 'SpotGarageZone', 'LedGarageZone', 'Light4GarageZone'];
        $visible = $this->ReadPropertyString('RoomStyle') === 'garage_kitchen';
        $walk = function (&$items) use (&$walk, $fields, $visible) {
            foreach ($items as &$item) {
                if (isset($item['name']) && in_array($item['name'], $fields, true)) $item['visible'] = $visible;
                if (isset($item['items'])) $walk($item['items']);
            }
            unset($item);
        };
        $walk($form['elements']);
        return json_encode($form, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    public function SetGarageFormVisibility(string $RoomStyle): void
    {
        foreach (['PendantGarageZone', 'SpotGarageZone', 'LedGarageZone', 'Light4GarageZone'] as $field) $this->UpdateFormField($field, 'visible', $RoomStyle === 'garage_kitchen');
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        // Apply this to existing instances as Create() is not called again after updates.
        $this->SetVisualizationType(defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')
            ? constant('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') : 1);
        // The visual title and the object-tree instance name are independent.
        foreach ($this->GetReferenceList() as $id) $this->UnregisterReference($id);
        foreach ($this->GetMessageList() as $id => $messages) {
            foreach ($messages as $message) $this->UnregisterMessage($id, $message);
        }
        foreach (array_unique($this->VariableProperties()) as $property) {
            $id = $this->ReadPropertyInteger($property);
            if (IPS_VariableExists($id)) {
                $this->RegisterReference($id);
                $this->RegisterMessage($id, VM_UPDATE);
            }
        }
        $this->UpdateVisualizationValue($this->Snapshot());
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        if ($Message === VM_UPDATE) $this->UpdateVisualizationValue($this->Snapshot());
    }

    public function GetVisualizationTile()
    {
        $html = str_replace('/*CM_EXTENSIONS*/', file_get_contents(__DIR__ . '/climate.js'), file_get_contents(__DIR__ . '/module.html'));
        $initial = '<style>' . file_get_contents(__DIR__ . '/climate.css') . '</style><script>window.handleMessage(' .
            json_encode($this->Snapshot(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ')</script>';
        return str_replace('</body>', $initial . '</body>', $html);
    }

    private function VariableProperties()
    {
        $result = ['ClimateActual', 'ClimateTarget', 'ClimateFeedback'];
        foreach (self::LIGHTS as $light) $result = array_merge($result, array_slice($light, 1));
        foreach (['Socket', 'Socket2'] as $p) {
            $result[] = $p . 'Control'; $result[] = $p . 'Status';
        }
        foreach (['Blind1', 'Blind2'] as $p) {
            foreach (['Move', 'Stop', 'Position', 'Feedback'] as $suffix) $result[] = $p . $suffix;
        }
        return $result;
    }

    private function Valid($property, $types)
    {
        $id = $this->ReadPropertyInteger($property);
        return IPS_VariableExists($id) && in_array(IPS_GetVariable($id)['VariableType'], $types, true);
    }

    private function Writable($property, $types)
    {
        if (!$this->Valid($property, $types)) return false;
        $v = IPS_GetVariable($this->ReadPropertyInteger($property));
        // CustomAction = 1 explicitly disables the standard action in Symcon.
        return empty($v['VariableIsLocked']) && ($v['VariableCustomAction'] ?: $v['VariableAction']) > 1;
    }

    private function CanPercent($property, $maximum)
    {
        return in_array($maximum, [1, 100, 255], true)
            && $this->Writable($property, $maximum === 1 ? [2] : [1, 2]);
    }

    private function Read($feedback, $command, $types)
    {
        foreach ([$feedback, $command] as $property) {
            if ($this->Valid($property, $types)) {
                $value = GetValue($this->ReadPropertyInteger($property));
                return is_float($value) && !is_finite($value) ? null : $value;
            }
        }
        return null;
    }

    private function Percent($value, $maximum, $invert = false)
    {
        if ($value === null) return null;
        $percent = max(0, min(100, (float)$value * 100 / max(1, $maximum)));
        return $invert ? 100 - $percent : $percent;
    }

    private function SocketEnabled($p)
    {
        return $this->ReadPropertyBoolean($p === 'Socket' ? 'ShowSocket' : 'Socket2Enabled')
            && $this->Valid($p . 'Control', [0]);
    }

    private function SceneImage()
    {
        $room = $this->ReadPropertyString('RoomStyle');
        $allowed = ['kitchen','hall','corridor','living','dining','guest_wc','bathroom','children','bedroom','guestroom','staircase','office','utility','technical','storage','garage_kitchen','neutral'];
        if (!in_array($room, $allowed, true)) $room = 'neutral';
        if ($room === 'neutral') $room = 'office';
        $path = __DIR__ . '/assets/rooms/' . $room . '.jpg';
        return is_file($path) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path)) : '';
    }

    private function Snapshot()
    {
        $result = [
            'sceneImage' => $this->SceneImage(),
            'title' => $this->ReadPropertyString('Title'),
            'showTitle' => $this->ReadPropertyBoolean('ShowTitle'),
            'showSummary' => $this->ReadPropertyBoolean('ShowSummary'),
            'room' => $this->ReadPropertyString('RoomStyle'),
            'backgroundColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('BackgroundColor')),
            'textColor' => '#' . sprintf('%06X', $this->ReadPropertyInteger('TextColor')),
            'lights' => [], 'sockets' => [], 'blinds' => [],
            'climate' => [
                'enabled' => $this->ReadPropertyBoolean('ClimateEnabled'),
                'name' => $this->ReadPropertyString('ClimateName'),
                'actual' => $this->Read('ClimateActual', 'ClimateActual', [1,2]),
                'target' => $this->Read('ClimateFeedback', 'ClimateTarget', [1,2]),
                'min' => $this->ReadPropertyInteger('ClimateMin'),
                'max' => $this->ReadPropertyInteger('ClimateMax'),
                'step' => $this->Valid('ClimateTarget', [1]) ? 1 : 0.5,
                'canSet' => $this->Writable('ClimateTarget', [1,2])
                    && $this->ReadPropertyInteger('ClimateMin') < $this->ReadPropertyInteger('ClimateMax')
            ]
        ];
        foreach (self::LIGHTS as $index => $light) {
            [$p, $sw, $sf, $dim, $df] = $light;
            $useSwitch = $this->ReadPropertyBoolean($p . 'UseSwitch');
            $useDim = $this->ReadPropertyBoolean($p . 'UseDim');
            $level = $this->Percent($this->Read($df, $dim, [1,2]), $this->ReadPropertyInteger($p . 'Maximum'));
            $on = $this->Read($sf, $sw, [0]);
            if ($on === null && !$useSwitch) $on = $level === null ? null : $level > 0;
            $result['lights'][] = [
                'id' => 'L' . ($index+1), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'),
                'name' => $this->ReadPropertyString($p . 'Name'), 'type' => $this->ReadPropertyString($p . 'Type'),
                'useSwitch' => $useSwitch, 'useDim' => $useDim,
                'canSwitch' => $this->Writable($sw, [0]), 'canDim' => $this->CanPercent($dim, $this->ReadPropertyInteger($p . 'Maximum')),
                'on' => $on, 'level' => $level,
                'x' => $this->ReadPropertyInteger($p . 'X'), 'y' => $this->ReadPropertyInteger($p . 'Y'),
                'scale' => $this->ReadPropertyInteger($p . 'Scale'),
                'autoPosition' => $this->ReadPropertyBoolean($p . 'AutoPosition')
            ];
        }
        if ($result['room'] === 'garage_kitchen') {
            foreach (self::LIGHTS as $i => $light) $result['lights'][$i]['garageZone'] = $this->ReadPropertyString($light[0] . 'GarageZone');
        }
        foreach (['Socket', 'Socket2'] as $i => $p) {
            $result['sockets'][] = [
                'id' => 'S' . ($i+1), 'enabled' => $this->SocketEnabled($p),
                'name' => $this->ReadPropertyString($p . 'Name'),
                'on' => $this->Read($p . 'Status', $p . 'Control', [0]),
                'canSwitch' => $this->Writable($p . 'Control', [0])
            ];
        }
        foreach (['Blind1', 'Blind2'] as $i => $p) {
            $result['blinds'][] = [
                'id' => 'B' . ($i+1), 'enabled' => $this->ReadPropertyBoolean($p . 'Enabled'),
                'name' => $this->ReadPropertyString($p . 'Name'),
                'useMove' => $this->ReadPropertyBoolean($p . 'UseMove'),
                'useStop' => $this->ReadPropertyBoolean($p . 'UseStop'),
                'usePosition' => $this->ReadPropertyBoolean($p . 'UsePosition'),
                'canMove' => $this->Writable($p . 'Move', [0]),
                'canStop' => $this->Writable($p . 'Stop', [0]),
                'canPosition' => $this->CanPercent($p . 'Position', $this->ReadPropertyInteger($p . 'Maximum')),
                'position' => $this->Percent($this->Read($p . 'Feedback', $p . 'Position', [1,2]),
                    $this->ReadPropertyInteger($p . 'Maximum'), $this->ReadPropertyBoolean($p . 'Invert'))
            ];
        }
        return json_encode($result, JSON_INVALID_UTF8_SUBSTITUTE);
    }

    private function Write($property, $value, $types)
    {
        if (!$this->Writable($property, $types)) throw new RuntimeException('Befehlsvariable oder Aktion fehlt: ' . $property);
        $id = $this->ReadPropertyInteger($property);
        $type = IPS_GetVariable($id)['VariableType'];
        $value = $type === 0 ? (bool)$value : ($type === 1 ? (int)round($value) : (float)$value);
        if (RequestAction($id, $value) === false) throw new RuntimeException('Befehl fehlgeschlagen: ' . $property);
    }

    private function WritePercent($property, $value, $maximum, $invert = false)
    {
        if (!is_numeric($value) || !is_finite((float)$value) || $value < 0 || $value > 100) {
            throw new InvalidArgumentException('Prozentwert muss zwischen 0 und 100 liegen.');
        }
        if (!$this->CanPercent($property, $maximum)) {
            throw new RuntimeException('Wertebereich oder numerische Befehlsvariable ungültig; 0–1 benötigt Float.');
        }
        $this->Write($property, ($invert ? 100-(float)$value : (float)$value) * $maximum / 100, [1,2]);
    }

    public function RequestAction($Ident, $Value)
    {
        try {
            $this->ExecuteAction($Ident, $Value);
        } catch (Throwable $error) {
            // HTML-SDK requestAction has no return value; report failures explicitly.
            $this->UpdateVisualizationValue(json_encode(['error' => true]));
            throw $error;
        }
    }

    private function ExecuteAction($Ident, $Value)
    {
        if ($Ident === 'ClimateTarget') {
            if (!$this->ReadPropertyBoolean('ClimateEnabled')) return;
            if (!is_numeric($Value) || !is_finite((float)$Value)) throw new InvalidArgumentException('Ungültige Solltemperatur.');
            $value = (float)$Value;
            $min = $this->ReadPropertyInteger('ClimateMin');
            $max = $this->ReadPropertyInteger('ClimateMax');
            if ($min >= $max || $value < $min || $value > $max) throw new InvalidArgumentException('Solltemperatur außerhalb der eingestellten Grenzen.');
            if (!$this->Writable('ClimateTarget', [1,2])) throw new RuntimeException('Sollwertvariable ist nicht bedienbar.');
            if ($this->Valid('ClimateTarget', [1])) {
                if (floor($value) !== $value) throw new InvalidArgumentException('Diese Sollwertvariable benötigt ganze Grad.');
                $value = (int)$value;
            }
            $this->Write('ClimateTarget', $value, [1,2]);
        } elseif (preg_match('/^L([1-4])(Switch|Dim)$/D', $Ident, $m)) {
            [$p, $sw, $sf, $dim, $df] = self::LIGHTS[(int)$m[1]-1];
            if (!$this->ReadPropertyBoolean($p . 'Enabled')) return;
            if ($m[2] === 'Switch' && $this->ReadPropertyBoolean($p . 'UseSwitch')) {
                $state = $this->Read($sf, $sw, [0]);
                if ($state === null) throw new RuntimeException('Schaltzustand unbekannt.');
                $this->Write($sw, !$state, [0]);
            } elseif ($m[2] === 'Dim' && $this->ReadPropertyBoolean($p . 'UseDim')) {
                $this->WritePercent($dim, $Value, $this->ReadPropertyInteger($p . 'Maximum'));
            }
        } elseif (preg_match('/^S([12])Switch$/D', $Ident, $m)) {
            $p = $m[1] === '1' ? 'Socket' : 'Socket2';
            if (!$this->SocketEnabled($p)) return;
            $this->Write($p . 'Control', !$this->Read($p . 'Status', $p . 'Control', [0]), [0]);
        } elseif (preg_match('/^B([12])(Up|Down|Stop|Position)$/D', $Ident, $m)) {
            $p = 'Blind' . $m[1];
            if (!$this->ReadPropertyBoolean($p . 'Enabled')) return;
            if (in_array($m[2], ['Up','Down'], true) && $this->ReadPropertyBoolean($p . 'UseMove')) {
                $down = $this->ReadPropertyBoolean($p . 'DownValue');
                $this->Write($p . 'Move', $m[2] === 'Down' ? $down : !$down, [0]);
            } elseif ($m[2] === 'Stop' && $this->ReadPropertyBoolean($p . 'UseStop')) {
                $this->Write($p . 'Stop', $this->ReadPropertyBoolean($p . 'StopValue'), [0]);
            } elseif ($m[2] === 'Position' && $this->ReadPropertyBoolean($p . 'UsePosition')) {
                $this->WritePercent($p . 'Position', $Value, $this->ReadPropertyInteger($p . 'Maximum'), $this->ReadPropertyBoolean($p . 'Invert'));
            }
        } else {
            throw new InvalidArgumentException('Unbekannter Bedienbefehl.');
        }
        $this->UpdateVisualizationValue($this->Snapshot());
    }
}
