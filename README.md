# CMelektro · Wohnbereich 2.0.1

Tablet-Hauptseite für Küche, Esszimmer und Wohnzimmer mit einem gemeinsamen Raumbild und einer zusätzlichen Bedienseite für die übrigen Räume. Für Symcon 9.0 und neuer. Autor: CMelektro.

## Korrektur einer bereits hochgeladenen Version 2.0.0

Der bisherige oberste Ordner `shared` war für Symcons Modulprüfung nicht zulässig. Die gemeinsamen Dateien liegen jetzt im reservierten Ordner `libs`. Der alte Ordner **`shared` muss im GitHub-Repository vollständig entfernt werden**; ein Upload neuer Dateien löscht ihn nicht automatisch. Danach den vollständigen Inhalt dieses Pakets hochladen und in Symcon aktualisieren. Erwartete Version: **2.0.1 / Build 4**.

Die Korrektur verändert keine Gerätezuordnung, Grafik oder Bedienfunktion. Modul- und Bibliothekskennungen bleiben gleich.

## Schnellstart

1. ZIP vollständig entpacken. Den gesamten **Inhalt des Ordners `Wohnbereich`** in das Wohnbereich-GitHub-Repository hochladen. Auf der obersten Ebene müssen `library.json`, `WohnbereichTile`, `WohnbereichOverview` und **`libs`** liegen. In `libs/assets` muss `panorama.jpg` enthalten sein. Nicht in das separate Raumkachel-Repository hochladen.
2. Falls die Wohnbereich-Bibliothek bereits installiert ist: In Symcon unter **Kern Instanzen → Modules → Auf Aktualisierung prüfen** aktualisieren. Erwartete Version: **2.0.1 / Build 4**. Bestehende Wohnbereich-Instanzen behalten ihre Zuordnungen. In der Instanzkonfiguration muss oben ebenfalls 2.0.1 stehen. Eine neue Versionsangabe allein bestätigt nicht, dass alle Unterordner hochgeladen wurden.
3. Falls noch nicht installiert: Ein eigenes Repository anlegen, dessen Inhalt wie oben hochladen und die Repository-URL in Modules hinzufügen. Danach eine Instanz **Wohnbereich – Tabletsteuerung** anlegen.
4. Küche, Esszimmer und Wohnzimmer in der Instanz konfigurieren: pro Bereich drei Lichtkreise, zwei Steckdosenkreise und zwei Jalousien. Kreise sind zunächst deaktiviert. Schalten, Dimmen, Auf/Ab, Stopp und absolute Position lassen sich separat aktivieren. Nur aktivierte Geräte und gewählte Funktionen erscheinen.
5. Den gemeinsamen Klimakreis bei Bedarf aktivieren und Ist-, Soll- und gegebenenfalls Sollwert-Rückmeldevariable zuordnen. Die Regelung bleibt im vorhandenen Heizungsaktor.
6. Die Instanz in einer eigenen, in der Visu sichtbaren Wohnbereich-Kategorie platzieren. In der Visu im Bearbeitungsmodus die Kachel auf die verfügbare Tabletfläche vergrößern und speichern. Das ist die normale Kachelgröße; die native Vergrößern-Schaltfläche ist unter Symcon 9.0 kein Ersatz dafür.

Die bisherigen Raumkachel- und Raumübersicht-Bibliotheken werden nicht ersetzt. Deren Instanzen, Variablenzuordnungen, Grafikdateien und Befehlslogik werden durch dieses Paket nicht verändert. Die gemeinsame Wohnbereichskachel verwendet weiterhin ihre eigenen Zuordnungen für Küche/Esszimmer/Wohnzimmer.

## Bedienung der Hauptseite

- Ein Tipp auf den Bereich im Raumbild oder auf den Reiter **Küche / Esszimmer / Wohnzimmer** zeigt die zugehörigen Lichtregler darunter. Das ganze Raumbild bleibt sichtbar.
- **Steckdosen** und **Jalousien** öffnen ein dunkles Bedienfenster für den gewählten Bereich. Ein Tipp außerhalb, × oder Escape schließt es. Die Bedienelemente sind echte HTML-Elemente.
- Die Temperaturanzeige oben öffnet Isttemperatur und Sollwertverstellung für den gemeinsamen Klimakreis.
- Titel und Objektbaum-Instanzname sind unabhängig. Der Titel und die Statuszeile sind abschaltbar, Text- und Hintergrundfarbe einstellbar.
- Leuchtenart pro Lichtkreis: Hängelampe, Stehlampe, Spots, LED-Leiste, Panelleuchte, Wandleuchte oder Wegelicht. Automatische Positionen sind an das neue gemeinsame Raumbild angepasst. Manuelle X/Y-/Größenangaben bleiben möglich.

Leuchten und ihre Lichtwirkung, Steckdosensymbole und sechs Jalousien werden über dem lokal gespeicherten, unbeleuchteten Hintergrund gezeichnet. Deaktivierte Geräte erscheinen nicht. Lichtkegel sind illustrativ; sie sind keine physikalische Lichtberechnung. Möbel und Architektur bleiben feste Teile des Motivs.

## Weitere Räume ohne Raumbilder

In der Instanz den Abschnitt **Weitere Räume · Bedienseite ohne Grafik** öffnen:

1. **Zusätzliche Raumseite aktivieren** einschalten.
2. Unter **Räume auf dem Tablet** auf **Hinzufügen** klicken.
3. Die bereits eingerichtete **CMelektro-Raumkachel-Instanz** des betreffenden Raums auswählen. Nicht die Kategorie oder Raumübersicht auswählen.
4. **Aktiv** markieren; optional einen eigenen Anzeigenamen eintragen. Ohne eigenen Namen wird der Titel der Quelle verwendet, ersatzweise deren Instanzname.
5. Für die gewünschten Räume wiederholen und Änderungen übernehmen. Die Reihenfolge lässt sich ändern. Bis zu 24 aktive, unterschiedliche Raumkachel-Instanzen werden berücksichtigt.

Danach erscheint oben rechts **Weitere Räume**. Auf dieser Seite links den Raum auswählen; rechts stehen die aktivierten Lichtkreise, Steckdosen, Jalousien und der optionale Klimakreis dieses Raums. Die Variablen und Geräteeinstellungen stammen aus der vorhandenen Raumkachel; keine zweite Variablenzuordnung ist nötig. **← Wohnbereich** führt zur Hauptseite zurück. Der Wechsel erfolgt innerhalb derselben Kachel und benötigt keine zusätzliche Visu-Kategorie. Bei vielen Funktionen bleibt die rechte Bedienfläche scrollbar, während die Raumauswahl erhalten bleibt.

Die Seite dient zur normalen Bedienung (Schalten, Dimmen, Jalousien, Sollwerte). Technische Zuordnungen, Leuchtenarten und Freischaltungen werden wie bisher in der Symcon-Instanzkonfiguration gepflegt. Befehle für die anderen Räume werden nur an die hier ausdrücklich aktivierten Quellinstanzen weitergegeben. Deaktivierte, gelöschte oder fremde Instanzen sind nicht bedienbar. Ein gemeinsamer Benutzerzugang zur Tabletseite erlaubt die Bedienung aller hier verknüpften Räume; die Auswahl daher passend zum vorgesehenen Benutzerkreis konfigurieren.

Die zusätzliche Raumseite funktioniert mit dem bestehenden CMelektro-Modultyp **Raumkachel** (Modul-ID `{95207FE5-8A45-4275-9F0A-0A4965D8CD58}`). Sie übernimmt bis zu vier Licht-, zwei Steckdosen-, zwei Jalousiekreise und einen Klimakreis pro Raum. Sie erfordert keine neue Version dieser Quellmodule. Eine beliebige fremde Geräteinstanz wird nicht als Raumquelle unterstützt.

## Rückmeldungen und Jalousiebefehle

Rückmeldungen haben Vorrang vor Befehlsvariablen. Fehlende Werte werden als unbekannt angezeigt. Dimmen unterstützt 0–100, 0–255 und 0–1 (Float). Ein alter Dimmwert kann bei ausgeschaltetem Licht gespeichert bleiben; der Lichtkegel bleibt dabei aus. Der Dimmwert wird erst beim Loslassen/Bestätigen gesendet.

Für getrennte KNX-Objekte eine boolesche Fahrvariable für Auf/Ab und eine **andere** boolesche Variable für Stopp/Schritt zuordnen. Auf-/Ab-Richtung und Stoppwert sind einstellbar. Gleiche Fahr-/Stoppvariablen werden für Stopp blockiert. Die Wohnbereichskreise unterstützen alternativ eine kombinierte Integer-Befehlsvariable mit drei unterschiedlichen, explizit einstellbaren Auf-/Stopp-/Ab-Werten. Wertebereich und Richtung der absoluten Position sind konfigurierbar. Die Bedienanzeige nutzt 0 % offen / 100 % geschlossen.

Auch auf der Seite Weitere Räume werden die Befehle an die bestehende Raumkachel übergeben; deren bereits funktionierende Zuordnung bleibt maßgeblich. Eine zusätzliche Prüfung verhindert dort Stopp über dieselbe Variable wie Fahren.

## Wohnbereich-Übersicht

Enthalten bleibt **Wohnbereich – Status und Navigation**. Als Quelle die Wohnbereich-Tabletinstanz auswählen. Sie zeigt dasselbe Motiv mit Status, ohne Bedienung der Geräte oder zusätzlichen Räume. Unter Symcon 9.0 eine sichtbare Ziel-Kategorie verwenden; ohne Ziel wird die nächste übergeordnete Kategorie der Quelle verwendet. Übersicht und Hauptkachel müssen für den Visu-Zugang sichtbar sein. Eine in der Objektbaum-Wurzel liegende Quelle benötigt eine Ziel-Kategorie.

## Kompatibilität, Aktualisierung und Vorschau

Symcon 9.0 verwendet den unterstützten HTML-SDK-Typ 1. Erst bei vorhandener SDK-Konstante für HTML-Vollbild wird dieser Typ genutzt. Die vom Symcon-Client bereitgestellte äußere Vergrößern-Schaltfläche wird nicht verändert. Es wird keine Funktion vorausgesetzt, die erst Symcon 9.1 benötigt.

Werteänderungen werden über VM_UPDATE übernommen. Änderungen an den verknüpften Raumkonfigurationen werden zusätzlich im Abstand von fünf Sekunden eingelesen. Unveränderte Zustände werden nicht erneut gesendet. Schrift und Bild werden ohne externe Server geladen.

`Wohnbereich-Tablet-Vorschau.html` ist eine eigenständige lokale Vorschau mit simulierten Zuständen und Befehlen. Zum Ausprobieren herunterladen und im Browser öffnen. Sie stellt keine Verbindung zur Anlage her.

Prüfumfang und Grenzen stehen in `PRUEFBERICHT.md`. Kein Test auf der tatsächlichen SymBox oder KNX-Anlage; dort nach der Installation die konkreten Variablenaktionen und Visu-Freigaben prüfen.
