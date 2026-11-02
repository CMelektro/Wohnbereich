<?php
final class WohnbereichWidget
{
    public static function VisualizationType(): int
    {
        return defined('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') ? constant('INSTANCE_VISUALIZATION_TYPE_HTML_FULLSCREEN') : 1;
    }

    public static function HTML(string $initial): string
    {
        $path = __DIR__ . '/assets/panorama.jpg';
        if (!is_file($path)) throw new RuntimeException('Wohnbereichbild fehlt. Bitte libs/assets/panorama.jpg hochladen.');
        $image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($path));
        $safe = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE;
        $html = file_get_contents(__DIR__ . '/module.html');
        $script = 'const PANORAMA_IMAGE=' . json_encode($image, $safe) . ';\n';
        $script = str_replace(';\n', ";\n", $script);
        $script .= file_get_contents(__DIR__ . '/renderer.js') . "\n" . file_get_contents(__DIR__ . '/app.js');
        return str_replace(['/*WB_STYLE*/', '/*WB_SCRIPT*/', '/*WB_INITIAL*/'],
            [file_get_contents(__DIR__ . '/style.css'), $script, json_encode(json_decode($initial, true), $safe)], $html);
    }

    public static function CategoryFor(int $target): int
    {
        $seen = [];
        while ($target > 0 && IPS_ObjectExists($target) && !isset($seen[$target])) {
            if (IPS_CategoryExists($target)) return $target;
            $seen[$target] = true;
            $target = IPS_GetParent($target);
        }
        return 0;
    }
}
