# Korrektur 2.0.1 / Build 4

Die bisherige Version 2.0.0 enthielt einen von Symcon nicht erlaubten gemeinsamen Stammordner `shared`. Der Inhalt wurde in den dokumentierten, reservierten Ordner **libs** verschoben; die PHP-Includes wurden angepasst. Grafik, Bedienung, Variablenzuordnungen, Modulkennungen und Bibliothekskennung sind unverändert.

## Bereits hochgeladenes Repository korrigieren

1. Im bestehenden GitHub-Repository **CMelektro/Wohnbereich** den alten Ordner **shared vollständig löschen** und diese Änderung speichern. Die übrigen Ordner behalten.
2. Das neue ZIP entpacken und den vollständigen Inhalt des Ordners **Wohnbereich** in dasselbe Repository hochladen. Neben library.json müssen jetzt WohnbereichTile, WohnbereichOverview und **libs** stehen. Der optionale tests-Ordner ist seit Symcon 4.4 ausdrücklich zulässig.
3. Mit Commit changes speichern.
4. In Symcon unter Modules auf Aktualisierungen prüfen und die bereits eingetragene Wohnbereich-Bibliothek aktualisieren. Erwartet: **2.0.1 / Build 4**. Nicht als zweite Bibliothek mit denselben Kennungen hinzufügen.

Ein Upload neuer Dateien entfernt den alten shared-Ordner nicht automatisch. Solange dieser auf der obersten Ebene vorhanden ist, bleibt die gemeldete Ordnerprüfung fehlerhaft.

## Prüfung

- Fehler der alten Fassung mit dem tatsächlichen ModuleLoader aus den offiziellen SymconStubs reproduziert: versucht shared/module.json zu lesen und schlägt fehl.
- Korrigierte Fassung mit demselben offiziellen Testloader geladen: ausschließlich die beiden richtigen Module einschließlich PHP-Klassen und SDK-Funktionsregistrierung erkannt.
- Zusätzlich eigenständige Strukturprüfung, 281 PHP-/Konfigurationsprüfungen und 100 Browser-/Bedien-/Layoutprüfungen bestanden. Die unabhängige HTML-Vorschau bleibt unverändert.

Die offiziellen Stubs dienen als lokales Testwerkzeug. Ein Test auf einer tatsächlichen SymBox steht weiterhin aus.

Offizielle Strukturregel: https://www.symcon.de/de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/struktur/
Offizielle Test-Stubs: https://github.com/symcon/SymconStubs
