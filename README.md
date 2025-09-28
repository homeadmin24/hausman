# Hausman - WEG-Verwaltungssystem

## Überblick

Hausman ist ein umfassendes Immobilienverwaltungssystem für deutsche Wohnungseigentümergemeinschaften (WEG). Es bietet Finanzverfolgung, Zahlungsverwaltung, Rechnungsverarbeitung und automatisierte Erstellung von Hausgeldabrechnungen.

## Tech Stack

- **Backend**: Symfony 7.2 (PHP 8.2+)
- **Datenbank**: MySQL 8.0 mit Doctrine ORM 3.3
- **Frontend**: 
  - Tailwind CSS 4.0
  - Flowbite 3.1.2 (UI Components)
  - Stimulus.js (via Symfony UX)
  - Turbo (Hotwire)
  - Webpack Encore
- **PDF-Generierung**: DomPDF
- **Datentabellen**: Simple-DataTables

## Projektstruktur

```
hausman/
├── assets/              # Frontend-Assets (JS, CSS, Controller)
├── backup/              # Datenbank-Backups
├── bin/                 # Konsolen-Skripte und Tools
├── config/              # Symfony-Konfiguration
├── data/                # Dokumente und Zahlungsdaten
├── migrations/          # Datenbank-Migrationen
├── public/              # Web-Root
├── src/
│   ├── Command/         # Konsolen-Befehle
│   ├── Controller/      # HTTP-Controller
│   ├── Entity/          # Doctrine-Entitäten
│   ├── Form/            # Formular-Typen
│   ├── Repository/      # Daten-Repositories
│   ├── Service/         # Business-Logik-Services
│   └── Twig/            # Twig-Erweiterungen
├── templates/           # Twig-Templates
├── tests/               # Unit- und Funktionstests
└── var/                 # Cache, Logs, generierte Dateien
```

## Kernfunktionen

### 1. Immobilienverwaltung
- **WEG (Wohnungseigentümergemeinschaft)**: Zentrale Verwaltung mehrerer Eigentumseinheiten
- **WEG-Einheiten**: Einzelne Eigentumseinheiten mit Eigentümerdetails, Stimmrechten und Miteigentumsanteilen

### 2. Finanzverwaltung
- **Zahlungsverfolgung**: Erfassung aller Finanztransaktionen (Einnahmen/Ausgaben)
- **Zahlungskategorien**: Klassifizierung von Zahlungen als Einnahmen oder Ausgaben
- **Kostenkonten**: Kontenplan mit Kennzeichnung umlagefähiger Kosten
- **Kontostandsverwaltung**: Automatisierte Saldenberechnung und Kontostandsentwicklung

### 3. Dienstleisterverwaltung
- **Dienstleister**: Verwaltung von Handwerkern und Dienstleistern mit Vertragsdetails
- **Rechnungsverwaltung**: Verknüpfung von Rechnungen mit Dienstleistern, Fälligkeitsdaten und Steuerinformationen
- **Arbeits-/Fahrtkosten**: Erfassung für §35a EStG Steuerabzug

### 4. Dokumentenverwaltung
- Speicherung und Organisation wichtiger Dokumente (Eigentümerdokumente, Beschlüsse, Jahresabschlüsse)
- Kategorisierung nach Typ: eigentuemer, umlaufbeschluss, jahresabschluss

### 5. Hausgeldabrechnungen
- Automatisierte Generierung von Hausgeldabrechnungs-PDFs und TXT-Dateien
- Berechnung der Eigentümeranteile basierend auf Miteigentumsanteilen (MEA)
- Trennung von umlagefähigen und nicht umlagefähigen Kosten
- Steuerlich absetzbare Leistungen nach §35a EStG
- Wirtschaftsplan für das Folgejahr
- Kontostandsentwicklung und Vermögensübersicht

## Datenbankschema

Das System verwendet **MySQL 8.0 mit Doctrine ORM 3.3** für umfassende WEG-Finanzverwaltung.

**Kern-Entitäten**: Weg, WegEinheit, Zahlung, Kostenkonto, Dienstleister, Rechnung, Dokument, Hausgeldabrechnung, MonatsSaldo

📖 **Vollständige Dokumentation**: [Database Schema Documentation](doc/TechnicalArchitecture/DATABASE_SCHEMA.md)

## Routen und Controller

### Hauptrouten

- `/` - Dashboard mit Finanzübersicht
- `/zahlung/` - Zahlungsverwaltung (Liste, Erstellen, Bearbeiten, Löschen)
- `/zahlung/new` - Neue Zahlung erfassen
- `/zahlung/{id}/edit` - Zahlung bearbeiten
- `/rechnung/` - Rechnungsverwaltung
- `/dienstleister/` - Dienstleisterverwaltung
- `/dokument/` - Dokumentenverwaltung

### Controller

1. **HomeController**: Dashboard und Statistiken
2. **ZahlungController**: Zahlungsübersicht
3. **ZahlungCreateController**: Neue Zahlungen erstellen
4. **ZahlungEditController**: Zahlungen bearbeiten
5. **ZahlungDeleteController**: Zahlungen löschen
6. **RechnungController**: Rechnungsverwaltung
7. **DienstleisterController**: Dienstleisterverwaltung
8. **DokumentController**: Dokumentenverwaltung

## Wichtige Services

### HausgeldabrechnungGenerator

Generiert Hausgeldabrechnungen als PDF und TXT mit:
- Gesamtkostenberechnung mit Rücklagenzuführung
- Eigentümeranteilsberechnung basierend auf MEA
- Trennung von umlagefähigen und nicht umlagefähigen Kosten
- Steuerlich absetzbare Leistungen nach §35a EStG
- Zahlungsübersicht und Kontostandsentwicklung
- Vermögensübersicht und Wirtschaftsplan

### CalculationService

Zentrale Berechnungslogik für:
- Kostenverteilung nach Umlageschlüsseln
- Dynamische Einheitenverteilung
- Hebeanlage-Spezialverteilung
- Externe Heiz- und Wasserkosten

## Konsolen-Befehle

### Hausgeldabrechnung generieren
```bash
php bin/console app:generate-hausgeldabrechnung <weg-id> <jahr> [--format=pdf|txt]
```
Generiert Hausgeldabrechnungen für alle Einheiten einer WEG.

### Monatssalden importieren
```bash
php bin/console app:import-monthly-balance <weg-id> <jahr> <monat> <saldo>
```
Importiert monatliche Kontostände für die Kontostandsentwicklung.

## Installation

1. Repository klonen
2. Abhängigkeiten installieren:
   ```bash
   composer install
   npm install
   ```
3. Datenbank in `.env` konfigurieren:
   ```
   DATABASE_URL="mysql://app:changeme@127.0.0.1:3306/hausman?serverVersion=8.0.32&charset=utf8mb4"
   ```
4. Datenbank erstellen und Migrationen ausführen:
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```
5. Fixtures laden (optional):
   ```bash
   php bin/console doctrine:fixtures:load
   ```
6. Frontend-Assets erstellen:
   ```bash
   npm run build
   ```

## Entwicklung

### Entwicklungsserver starten
```bash
symfony server:start
npm run watch
```

### Code-Qualitäts-Tools
- PHP CS Fixer: `vendor/bin/php-cs-fixer fix`
- PHPStan: `vendor/bin/phpstan analyse`
- PHPUnit: `php bin/phpunit`

## Frontend-Architektur

### Stimulus-Controller
- `modal_controller.js`: Modal-Dialog-Verwaltung
- `zahlung_controller.js`: Zahlungsbezogene UI-Interaktionen
- `zahlung_form_controller.js`: Formularverarbeitung für Zahlungen
- `csrf_protection_controller.js`: CSRF-Token-Verwaltung

### Styling
- Tailwind CSS 4.0 mit PostCSS
- Flowbite UI-Komponenten
- Eigene Twig-Erweiterung für Tailwind-Klassen-Verwaltung

## Sicherheitsfunktionen

- CSRF-Schutz bei allen Formularen
- Symfony Security Bundle Integration
- Umgebungsbasierte Konfiguration
- Sichere Session-Verwaltung

## Deutsche Steuerrechtliche Besonderheiten

Das System enthält spezielle Funktionen für deutsches Steuerrecht:
- Haushaltsnahe Dienstleistungen (§35a EStG) mit Arbeits-/Fahrtkosten-Tracking
- Trennung von umlagefähigen und nicht umlagefähigen Kosten
- Ordnungsgemäße MwSt-Erfassung und -Berichterstattung
- Unterstützung für Handwerkerleistungen und haushaltsnahe Dienstleistungen

## Zahlungskategorien

Das System verwendet ein datenbankgesteuertes Kategoriesystem:

**AUSGABEN (Negative Beträge):**
1. **Rechnung von Dienstleister** - Rechnungen von Dienstleistern
2. **Direktbuchung Kostenkonto** - Direkte Kostenkontobuchungen
3. **Auslagenerstattung Eigentümer** - Erstattungen an Eigentümer
4. **Rückzahlung an Eigentümer** - Rückzahlungen an Eigentümer
5. **Bankgebühren** - Bankgebühren

**EINNAHMEN (Positive Beträge):**
6. **Hausgeld-Zahlung** - Monatliche Hausgeldvorschüsse
7. **Sonderumlage** - Sonderumlagen
8. **Gutschrift Dienstleister** - Gutschriften von Dienstleistern
9. **Zinserträge** - Zinserträge
10. **Sonstige Einnahme** - Weitere Einnahmen

**NEUTRAL (Nullbeträge erlaubt):**
11. **Umbuchung** - Interne Umbuchungen
12. **Korrektur** - Korrekturbuchungen

## Zukünftige Erweiterungen

- API-Endpunkte für externe Integrationen
- Erweiterte Berichts- und Analysefunktionen
- Automatisierter Kontoauszugsimport
- Mieterportal für Nebenkostenabrechnungen
- Integration mit DATEV
- Elektronische Beschlussfassung