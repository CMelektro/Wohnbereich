# Update 2.0.1 / Build 4

Neue Tabletgestaltung nach dem freigegebenen Entwurf: gemeinsames professionelles Raumbild, eigene Kopfzeile mit gemeinsamem Klimakreis, Bereichsauswahl und drei Lichtkarten, Steckdosen-/Jalousiebedienung in kompakten Fenstern.

Zusätzlich: weitere Räume ohne Bilder direkt im selben Widget bedienen. Unter **Weitere Räume · Bedienseite ohne Grafik** vorhandene CMelektro-Raumkachel-Instanzen auswählen und aktivieren. Keine erneute Variablenzuordnung für diese Räume. Die Schaltfläche erscheint, sobald mindestens eine aktive Raumquelle konfiguriert ist.

Die GUIDs, Modulnamen und Präfixe der bisherigen Wohnbereich-Bibliothek bleiben erhalten. Bestehende Wohnbereich-Instanzen und Zuordnungen können weiterverwendet werden. Die separaten Raumkachel-/Raumübersicht-Bibliotheken werden nicht ersetzt oder geändert. Das neue Motiv ändert die Bildgestaltung der Wohnbereich-Instanzen; die Werte und Hauptbefehlslogik bleiben erhalten.

Vollständigen Inhalt des Ordners **Wohnbereich** in das passende Wohnbereich-Repository hochladen: `library.json`, `WohnbereichTile`, `WohnbereichOverview`, **libs mit allen Unterordnern**. `libs/OtherRooms.php` und `libs/assets/panorama.jpg` werden benötigt. Anschließend in Symcon aktualisieren und die Konfiguration/Visu neu öffnen. Die Überschrift der Instanzkonfiguration muss ebenfalls 2.0.1 anzeigen.

Zur Einrichtung siehe README.md.
