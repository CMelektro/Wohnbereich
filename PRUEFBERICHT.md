# Prüfumfang · Wohnbereich 2.0.1 / Build 4

## Ergebnis und Korrektur der Installationsprüfung

Die Ordnerprüfung des Installationslaufs fehlte im bisherigen Testumfang. Dadurch blieb der unzulässige Stammordner shared trotz bestandener Bedienprüfungen unentdeckt. Version 2.0.1 verschiebt ausschließlich die gemeinsamen Dateien nach libs und korrigiert die Include-Pfade.

- Fehler mit dem tatsächlichen offiziellen SymconStubs-ModuleLoader an Version 2.0.0 reproduziert: shared/module.json fehlt.
- Version 2.0.1 mit demselben Testloader erfolgreich geladen: zwei Module, PHP-Klassen und exportierte SDK-Funktionen registriert.
- 27 eigenständige Struktur-/Metadaten-/Include-Prüfungen ergänzt (tests/structure.php).
- **281 PHP-/Konfigurationsprüfungen und 100 Browser-/Bedien-/Layoutprüfungen erneut bestanden.** Die neun zuvor bestandenen Prüfungen der separaten Vorschau wurden nicht erneut ausgeführt, da diese Datei unverändert bleibt.

Es handelt sich um lokale Tests. Die tatsächliche SymBox-Installation wird durch diese Prüfung nicht ersetzt.

## PHP und Zuordnungen

- Alle bisherigen Wohnbereichsfunktionen weiterhin geprüft: neun unabhängige Lichtkreise, sechs Steckdosenkreise, sechs Jalousien und ein gemeinsamer Klimakreis.
- Schalten und Dimmen schreiben ausschließlich ihre jeweiligen Ziele. Rückmeldung hat Vorrang; Prozentbereiche 100/255/1, Float/Integer sowie invertierte Jalousiepositionen geprüft.
- Separate Auf-/Ab- und Stoppziele sowie explizite kombinierte Integer-Befehle geprüft. Gleiche Fahr-/Stoppvariable und doppelte kombinierte Befehlswerte blockiert.
- Deaktivierte Bereiche/Kreise/Funktionen, fehlende oder gesperrte Aktionen, CustomAction=1, ungültige Prozent-/Temperaturwerte und fehlgeschlagene nachgelagerte Befehle geprüft.
- Formularfelder decken die registrierten Eigenschaften ab; eindeutige Namen und JSON geprüft. SelectInstance verwendet den dokumentierten Filter `validModules`; die Liste benötigt keine Symcon-9.1-Funktion.
- Weitere Räume lesen nur die freigegebenen Quellen. Doppelte/deaktivierte Einträge, falscher Modultyp, Abmeldung und Referenz-/Abonnementbereinigung geprüft.
- Weiterleitung für Licht, Dimmen, Steckdosen, Auf/Ab, Stopp, Position und Solltemperatur gegen die echte bisherige Raumkachel-RequestAction geprüft. Unzulässige Quellinstanzen und Befehle erreichen keine Variablenaktion.
- Quellkonfiguration wird nachgelesen; VM_UPDATE und Polling übernehmen externe Werte. Unveränderte Zustände werden nicht erneut gesendet.
- Bis zu 24 Raumquellen geprüft. Eingebettete HTML-Ausgabe bleibt unter 1 MB. Repräsentative Ausgabe mit zwei weiteren Räumen etwa 264 KB.
- Übersicht bleibt rein lesend, übernimmt keine zusätzlichen Raumsteuerungen und navigiert unter Symcon 9.0 zu einer Kategorie. Die 9.1-Vollbildfähigkeit wird nur bei tatsächlich vorhandener SDK-Konstante genutzt.
- Bestehende Modulkennungen und Befehls-Schreibfunktionen mit 1.1.0 verglichen und unverändert. Kein IPS_SetName; Titel bleibt vom Instanznamen unabhängig.

## Browser und Bedienung

- Die echten PHP-HTML-Ausgaben für Hauptseite und Übersicht initialisieren ohne JavaScript-Fehler; das eingebettete JPEG wird decodiert.
- Hauptseite bei 1024×600, 1280×720, 1280×800 und 1920×1080 geprüft: kein horizontales oder vertikales Seiten-Scrollen, vollständiger Bedienstreifen und großes Raumbild.
- Bereichswechsel per Reiter und Raumbild, Zusatzseite, Raumauswahl und Rückkehr geprüft. Statusupdates setzen die gewählte Zusatzseite nicht zurück.
- Steckdosen-/Jalousiefenster, Escape zum Schließen, getrennte Befehle, Slider und Sollwertänderungen geprüft. Ein geöffnetes Klimafenster verwendet nach Rückmeldungen den aktuellen Sollwert.
- Alle sieben Leuchtenarten für alle neun Lichtkreise gerendert. Deaktivierte Leuchten/Karten und nicht gewählte Schalt-/Dimmfunktionen fehlen; Lichtwirkung folgt Ein/Aus und Dimmwert.
- Übersicht bei 600×400, 450×300 und 360×260 geprüft; keine Gerätebedienung, Navigation über openObject.
- Separate lokale Vorschau vollständig bedienbar: Szene, Dimmen, Jalousie, gemeinsames Klima und zusätzliche Räume. Keine externen HTTP-Anfragen und keine JavaScript-Fehler.

## Sichtprüfung

Hauptseite und zusätzliche Bedienseite wurden gerendert betrachtet. Möbel und Gehbereiche bleiben Teil einer einheitlichen Architektur. Fenster/Jalousien sind den sechs Scheiben zugeordnet. Leuchten besitzen Aufhängungen oder Ständer; Lichtkegel beginnen an den Quellen. Die Stehlampe steht im Zwischenraum von Essplatz und Sofa, Steckdosensymbole liegen auf den Wand-/Rückwandbereichen. Lampen, Lichtwirkung und Lamellen sind nicht in das Hintergrundbild eingebrannt. Licht- und Schattenwirkung sind illustrative Overlays; keine physikalische Lichtsimulation oder vollständige dynamische 3D-Szene.

## Grenze der Aussage

Nicht auf einer echten SymBox, in der mobilen Symcon-App oder an den konkreten KNX-Aktoren getestet. Nach Installation müssen insbesondere Variablenaktionen, tatsächliche Auf-/Ab-/Stoppbelegung, Rückmeldeadressen und Visu-Freigaben an der Anlage geprüft werden. Die native äußere Maximieren-Schaltfläche wird vom Symcon-Client bereitgestellt und nicht durch diese Kachel entfernt; unter 9.0 die normale Kachel auf Tabletgröße einstellen.
