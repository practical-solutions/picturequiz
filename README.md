# DokuWiki-Plugin: PictureQuiz

Bilder können mit verdeckten Bereichen versehen werden, die per Mausklick aufgedeckt werden.

## Usage

The coding area mus be enclosed with the ``<picturequiz>...</picturequiz>``-Tags.

The following options can be used:
* ``image:`` - image file (in DokuWiki-syntax)
* ``button: `` - draws a button (rectangle) between the stated coordinates
* ``edit_mode`` - display a helping text area to design buttons - optional
* ``hide_button`` - hide text over buttons - options

Example:

```
<picturequiz>
image: wiki:example.png

button: [x],[y],[x2],[y2]

edit_mode

hide_button_text

</picturequiz>
```

## Ideen / Verbesserungen

* Code CleanUp
* Sprachdateien
* ``quelle:``-Tag

### Ausblick: Ablage der Metadaten in einer PNG?

Recherche bisher:
* https://stackoverflow.com/questions/25528264/appending-a-png-phys-chunk-in-php/46541839#46541839
