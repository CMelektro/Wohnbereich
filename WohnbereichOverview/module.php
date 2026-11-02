<?php
require_once __DIR__ . '/../libs/Widget.php';
class WohnbereichOverview extends IPSModule
{
    private const SOURCE_MODULE = '{07E9D048-9A26-4F61-8E32-6E45CCD21BF1}';
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyInteger('Source', 0);
        $this->RegisterPropertyInteger('TargetCategory', 0);
        $this->RegisterPropertyString('Title', 'Wohnbereich');
        $this->RegisterPropertyBoolean('ShowTitle', true);
        $this->RegisterPropertyBoolean('ShowTemperature', true);
        $this->RegisterTimer('Refresh', 5000, 'WBRO_Refresh($_IPS[\'TARGET\']);');
        $this->SetVisualizationType(WohnbereichWidget::VisualizationType());
    }
    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetVisualizationType(WohnbereichWidget::VisualizationType());
        $this->SetBuffer('SnapshotHash', '');
        $this->SetBuffer('WatchKey', '');
        $this->Refresh();
    }
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        if ($Message === VM_UPDATE) $this->Refresh();
    }
    private function ValidSource(): int
    {
        $id = $this->ReadPropertyInteger('Source');
        return IPS_InstanceExists($id) && IPS_GetInstance($id)['ModuleInfo']['ModuleID'] === self::SOURCE_MODULE ? $id : 0;
    }
    private function State(): string
    {
        $source = $this->ValidSource();
        $state = ['connected' => false, 'areas' => [], 'climate' => ['enabled' => false], 'message' => 'Bitte in den Einstellungen eine Wohnbereich-Instanz als Quelle auswählen.'];
        if ($source && function_exists('WBR_GetState')) {
            try { $state = json_decode(WBR_GetState($source), true, 512, JSON_THROW_ON_ERROR); }
            catch (Throwable $e) { $state['message'] = 'Wohnbereich-Quelle konnte nicht gelesen werden: ' . $e->getMessage(); }
        }
        $target = $this->ReadPropertyInteger('TargetCategory');
        if (!$target) $target = $source;
        if ($target && !(IPS_CategoryExists($target) || ($target === $source && defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN')))) $target = WohnbereichWidget::CategoryFor($target);
        unset($state['otherRooms'], $state['otherRoomsTitle']);
        $state['mode'] = 'overview';
        $state['title'] = $this->ReadPropertyString('Title');
        $state['showTitle'] = $this->ReadPropertyBoolean('ShowTitle');
        $state['showSummary'] = true;
        $state['target'] = !empty($state['connected']) ? $target : 0;
        $state['navigationHint'] = $target ? '' : 'Ziel-Raumkategorie auswählen oder die Wohnbereich-Instanz in ihre Raumkategorie einordnen.';
        $state['climate']['enabled'] = !empty($state['climate']['enabled']) && $this->ReadPropertyBoolean('ShowTemperature');
        $state['climate']['canSet'] = false;
        foreach ($state['areas'] as &$area) {
            foreach (['lights','sockets','blinds'] as $kind) foreach ($area[$kind] as &$device) {
                foreach (['canSwitch','canDim','canMove','canStop','canPosition'] as $key) $device[$key] = false;
            }
            unset($device);
        }
        unset($area);
        return json_encode($state, JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }
    public function Refresh(): void
    {
        $ids = []; $source = $this->ValidSource();
        if ($source && function_exists('WBR_GetWatchVariables')) $ids = WBR_GetWatchVariables($source);
        $key = json_encode([$source, $ids]);
        if ($key !== $this->GetBuffer('WatchKey')) {
            foreach ($this->GetReferenceList() as $id) $this->UnregisterReference($id);
            foreach ($this->GetMessageList() as $id => $messages) foreach ($messages as $message) $this->UnregisterMessage($id, $message);
            if ($source) $this->RegisterReference($source);
            $target = $this->ReadPropertyInteger('TargetCategory');
            if (IPS_ObjectExists($target)) $this->RegisterReference($target);
            foreach ($ids as $id) if (IPS_VariableExists($id)) { $this->RegisterReference($id); $this->RegisterMessage($id, VM_UPDATE); }
            $this->SetBuffer('WatchKey', $key);
        }
        $state = $this->State(); $hash = hash('sha256', $state);
        if ($hash !== $this->GetBuffer('SnapshotHash')) { $this->SetBuffer('SnapshotHash', $hash); $this->UpdateVisualizationValue($state); }
    }
    public function GetVisualizationTile(): string { return WohnbereichWidget::HTML($this->State()); }
    public function RequestAction($Ident, $Value) { throw new RuntimeException('Diese Übersicht dient nur der Anzeige und Navigation.'); }
}
