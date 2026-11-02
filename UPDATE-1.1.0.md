# Wohnbereich 1.1.0 · Ein gemeinsames Raumbild

Die Hauptseite und die Übersicht zeigen jetzt **ein einziges zusammenhängendes Wohnbereichsbild** mit Küche links, Essplatz in der Mitte und Wohnzone rechts. Kontinuierliche Wand, Boden und Perspektive ersetzen die drei getrennten Szenen.

## Bedienung auf dem Tablet

- Das vollständige Wohnbereichsbild bleibt sichtbar. Alle aktivierten Leuchten, Steckdosen und Jalousien werden gemeinsam darin dargestellt.
- Einen Bereich **im Bild oder über Küche / Esszimmer / Wohnzimmer unter dem Bild antippen**. Darunter erscheinen die drei Licht-, zwei Steckdosen- und zwei Jalousiekreise dieses Bereichs.
- Es wird nur die Bedienung des gewählten Bereichs eingeblendet. Die Kreise der anderen Bereiche werden dadurch nicht deaktiviert oder verändert.
- Die gemeinsame Ist-/Solltemperatur bleibt oben erreichbar, wenn der Klimakreis aktiviert ist.
- Die Übersicht verwendet dasselbe Bild in kleiner Darstellung mit Statusangaben. Dort bleiben direkte Gerätebefehle und Sollwertbedienung ausgeschlossen.

## Update einer vorhandenen Wohnbereich-Bibliothek

ZIP entpacken und den **Inhalt** von `Wohnbereich` in das bestehende Wohnbereich-Repository hochladen. `library.json`, `WohnbereichTile`, `WohnbereichOverview` und `libs` liegen direkt auf der obersten Ebene. Besonders die neue Datei `libs/assets/panorama.jpg` sowie alle aktualisierten Dateien in `libs` übertragen. Ältere ungenutzte Einzelraumbilder dürfen im Repository liegen bleiben; sie werden nicht mehr geladen.

In Symcon die Bibliothek auf **1.1.0 / Build 2** aktualisieren und die Visu vollständig neu öffnen. Die beiden vorhandenen Instanzen können bleiben. Modulkennungen, Variablenfelder und beide PHP-Module sind unverändert; vorhandene Zuordnungen bleiben erhalten. Kein neues Repository nötig, wenn Wohnbereich 1.0.0 schon installiert ist.

Bei erstmaliger Installation die Schritte in README.md beachten. Die weiterhin separat installierten Einzelraumkacheln und deren Raumübersichten werden nicht verändert.

## Geprüft

- 211 Backend-/Konfigurationsprüfungen bestanden.
- 2.807 Interaktions-, Sichtbarkeits-, Grafik- und Übersichtsprüfungen bestanden.
- 43 Prüfungen des neuen Bildlayouts bestanden: ein Hintergrundbild, Bereichsauswahl und alle drei Bedienstreifen bei 1280 × 800, 1024 × 600 und 1280 × 720; kompakte Übersicht bei 600 × 400, 450 × 300 und 360 × 260.
- Neun End-to-End-Prüfungen der aktualisierten interaktiven Vorschau bestanden.
- Das Bild mit aktivierten Leuchten und Jalousien gerendert betrachtet. Lichtpositionen an das gemeinsame Motiv angepasst: Stehlampe neben dem Sofa, Küchen-LED am Arbeitsbereich, Esszimmer-LED als Wandleiste und Wohnraum-LED unter dem TV-Möbel.

Es besteht weiterhin kein Zugriff auf die echte SymBox oder KNX-Anlage. Die Lichtdarstellung ist illustrativ; Möbelschatten und Reflexionen werden nicht physikalisch berechnet. Unter 520 Pixeln nutzbarer Höhe kann die Hauptseite vertikal scrollen; schmale Ansichten ordnen die Bedienelemente anders an.
