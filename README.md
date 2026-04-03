# Kalvenes Pamatskola Website

Pilnvērtīga Kalvenes Pamatskolas mājaslapa ar publisko daļu un satura pārvaldības paneli. Projekts ir veidots PHP, MySQL, HTML, CSS un JavaScript vidē un paredzēts darbam lokāli ar XAMPP.

## Pārskats

Šī repozitorija mērķis ir uzturēt skolas mājaslapu, kurā apmeklētāji var:

- apskatīt galveno lapu ar hero video;
- lasīt aktualitātes un detalizētus ierakstus;
- iepazīt skolas priekšrocības, vēsturi un sasniegumus;
- atrast uzņemšanas informāciju un lejupielādēt dokumentus;
- nosūtīt ziņu caur kontaktformu.

Projektā ir iekļauts arī administrācijas panelis, kur var atjaunināt galvenās lapas saturu, kontaktinformāciju un pārvaldīt saņemtās ziņas.

## Galvenās iespējas

### Publiskā daļa

- Dinamiska sākumlapa ar hero video un datiem no MySQL.
- Aktualitāšu sadaļa ar attēliem, īso tekstu un detalizēto skatu.
- Timeline jeb “Kāpēc izvēlēties Kalvenes pamatskolu?” sadaļa.
- Atsevišķas lapas: par skolu, dokumenti, sasniegumi, uzņemšana, vēsture, kontakti.
- Responsīva navigācija mobilajām ierīcēm.
- Gaišais un tumšais režīms.
- Facebook saite un Google Maps adrese kontaktu sadaļā.

### Administrācijas panelis

- Hero virsraksta, apakšvirsraksta un video atjaunināšana.
- Aktualitāšu pievienošana un dzēšana.
- Timeline ierakstu pievienošana.
- Kontaktinformācijas atjaunināšana.
- Saņemto kontaktformas ziņu apskate.
- Ziņu “soft delete” dzēšana, atzīmējot ierakstu kā dzēstu.

## Izmantotās tehnoloģijas

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- XAMPP
- Font Awesome
- Google Fonts

## Projekta struktūra

```text
Kalvenes_Pamatskola/
|-- Control-Panel/          # Satura pārvaldības panelis un augšupielādes
|-- Kontakti/               # Kontaktu lapa un kontaktforma
|-- Pienemsana/             # Uzņemšanas informācija
|-- Skola/                  # Par skolu, dokumenti, sasniegumi
|-- SkolaMainPage/          # Sākumlapa, detalizētās lapas, kopējais JS/CSS
|-- Skolasvest/             # Skolas vēstures lapa
|-- school_site.sql         # Datubāzes struktūra un sākotnējie dati
|-- README.md
`-- LICENSE
```

## Sistēmas prasības

- Windows ar XAMPP, vai cita vide ar PHP un MySQL/MariaDB.
- Apache un MySQL servisi.
- PHP 8.x vide.
- Datubāze ar nosaukumu `school_site`.

## Lokālā palaišana

### 1. Novieto projektu XAMPP mapē

Projekta mapei jāatrodas šeit:

```text
c:\xampp\htdocs\Kalvenes_Pamatskola
```

### 2. Importē datubāzi

Atver phpMyAdmin un importē failu:

```text
school_site.sql
```

Tas izveidos nepieciešamās tabulas, piemēram:

- `content`
- `aktualitates`
- `timeline`
- `contacts`
- `contact_submissions`
- `achievements`
- `about_us`
- `admissions`
- `school_history`

### 3. Pārbaudi datubāzes savienojumu

Pašlaik projekts izmanto šādu lokālo konfigurāciju:

```php
new mysqli("localhost", "root", "", "school_site");
```

To izmanto gan publiskā daļa, gan administrācijas panelis. Ja tava lokālā vide atšķiras, atjaunini savienojuma iestatījumus attiecīgajos PHP failos.

### 4. Palaid Apache un MySQL

XAMPP vadības panelī ieslēdz:

- Apache
- MySQL

### 5. Atver mājaslapu pārlūkā

Publiskā sākumlapa:

```text
http://localhost/Kalvenes_Pamatskola/SkolaMainPage/Lapa.php
```

Administrācijas panelis:

```text
http://localhost/Kalvenes_Pamatskola/Control-Panel/index.php
```

## Svarīgākās lapas

- `SkolaMainPage/Lapa.php` - sākumlapa
- `SkolaMainPage/detail.php` - detalizēts aktualitāšu un timeline ierakstu skats
- `Kontakti/KontaktiMain.php` - kontakti un kontaktforma
- `Pienemsana/Uznemsana.php` - uzņemšanas informācija
- `Skolasvest/Vesture.php` - skolas vēsture
- `Skola/SasniegumiNav.php` - sasniegumu lapa
- `Control-Panel/index.php` - satura pārvaldība

## Datu plūsma

- Sākumlapa lasa saturu no `content`, `aktualitates` un `timeline` tabulām.
- Kontaktu lapa lasa datus no `contacts` un saglabā jaunas ziņas `contact_submissions` tabulā.
- Administrācijas panelis atjaunina sākumlapas saturu, kontaktus un pievieno jaunus ierakstus.
- Augšupielādētie faili tiek saglabāti `Control-Panel/uploads/` vai `SkolaMainPage/SkolasAtteli/` mapēs atkarībā no satura veida.

## Piezīmes izstrādei

- Projekts ir veidots kā tradicionāla PHP daudzlapu mājaslapa bez atsevišķa būvēšanas soļa.
- Daļa lapu ir `.html`, daļa `.php`, jo vairākas sadaļas pakāpeniski pārgājušas uz dinamisku saturu.
- Administrācijas panelis šobrīd ir tieši pieejams pēc URL. Pirms publiskas izvietošanas ieteicams pievienot autentifikāciju un piekļuves kontroli.

## Autors

Daniels Dans Kveders

## Licence

Skatīt `LICENSE` failu repozitorijā.
