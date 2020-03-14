# PictureQuiz

Aktuell nur eine Code-Sicherung. Ursprünglich ein WP-Plugin, diese Fassung dann eingebettet in DokuWIki, aber nicht als 
Plugin in. Ziele
* echtes WP-Plugin daraus erstellen
* echts DW-Plugin erstellen
* Ablage der Metadaten in einer PNG selbst.

## Einbettung in DokuWiki

Die `picquiz.php`-Datei war im DW-Stammverzeichnis. Die sonstigen Datein unter `/picturequiz/` abgelegt. Einbettung in DokuWiki dann 
über
```
<php>
$pic = "uterus-gefaesse1.txt";
$edit="no";

include "picturequiz/show.php";
</php>
```
auf der Seite.

## Ablage der Metadaten in einer PNG

Recherche bisher:
* https://stackoverflow.com/questions/25528264/appending-a-png-phys-chunk-in-php/46541839#46541839
