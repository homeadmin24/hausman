# Hausman - WEG-Verwaltungssystem

[![Deploy to DO](https://www.deploytodo.com/do-btn-blue.svg)](https://cloud.digitalocean.com/apps/new?repo=https://github.com/homeadmin24/hausman/tree/main)

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

### 6. CSV-Import & Auto-Kategorisierung
- Automatischer Import von Kontoauszügen (Sparkasse SEPA-Format)
- Intelligente Auto-Kategorisierung mit Pattern-Matching
- Fuzzy-Matching für Eigentümer-Zuordnung
- Duplikatserkennung (3-stufiges Fallback-System)
- Automatische Erstellung neuer Dienstleister

## Dokumentation

📚 **Vollständige Projektdokumentation**: [CLAUDE.md](CLAUDE.md)

Die Dokumentation ist in drei Hauptkategorien organisiert:
- **BusinessLogic/** - WEG-Geschäftslogik, Finanzberechnungen, Steuerrecht (Rücklagenzuführung, §35a EStG)
- **CoreSystem/** - Anwendungsfunktionen (CSV-Import, Zahlungskategorien, Authentifizierung)
- **TechnicalArchitecture/** - Implementierung, Datenbankschema, Architektur-Entscheidungen

## Datenbankschema

Das System verwendet **MySQL 8.0 mit Doctrine ORM 3.3** für umfassende WEG-Finanzverwaltung.

📖 **Detailliertes Schema**: [Database Schema Documentation](doc/TechnicalArchitecture/DATABASE_SCHEMA.md)

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

## Installation & Bereitstellung

### Option 1: Mit Docker (Empfohlen für lokale Entwicklung)

1. Repository klonen:
   ```bash
   git clone https://github.com/homeadmin24/hausman.git
   cd hausman
   ```

2. Docker-Container starten:
   ```bash
   docker-compose up -d
   ```

3. In den Web-Container wechseln und Abhängigkeiten installieren:
   ```bash
   docker exec -it hausman-web-1 bash
   composer install
   npm install
   npm run build
   ```

4. Datenbank-Migrationen ausführen:
   ```bash
   docker exec hausman-web-1 php bin/console doctrine:migrations:migrate --no-interaction
   ```

5. Systemkonfiguration laden:
   ```bash
   docker exec hausman-web-1 php bin/console doctrine:fixtures:load --group=system-config --no-interaction
   ```

6. Admin-Benutzer erstellen:
   ```bash
   docker exec hausman-web-1 php bin/console app:create-admin
   ```

7. Anwendung öffnen:
   - Web: http://localhost:8000
   - MySQL: localhost:3307 (root/rootpassword)

**Docker-Befehle:**
```bash
# Container starten
docker-compose up -d

# Container stoppen
docker-compose down

# Logs anzeigen
docker logs hausman-web-1 -f

# In Web-Container Shell
docker exec -it hausman-web-1 bash

# Datenbank-Backup
./bin/backup_db.sh "beschreibung"
```

---

### Option 2: DigitalOcean App Platform (One-Click Cloud Deployment)

Klicken Sie auf den Button oben, um Hausman mit einem Klick auf DigitalOcean bereitzustellen:

1. Klicken Sie auf den "Deploy to DO" Button oben im README
2. Verbinden Sie Ihr GitHub-Repository
3. DigitalOcean erstellt automatisch:
   - PHP-Web-Service mit Nginx
   - MySQL 8.0 Datenbank
   - Automatische SSL-Zertifikate
   - HTTPS-Zugriff mit eigener Domain
4. Nach der Bereitstellung via DigitalOcean Console:
   ```bash
   # Admin-Benutzer erstellen
   php bin/console app:create-admin

   # Systemkonfiguration laden
   php bin/console doctrine:fixtures:load --group=system-config --no-interaction
   ```

**Vorteile:**
- ✅ Keine Serverkonfiguration notwendig
- ✅ Automatische Backups
- ✅ SSL-Zertifikate inklusive
- ✅ Skalierbar (bei Bedarf mehr Ressourcen)
- ✅ Automatische Updates bei Git-Push

**Kosten:** Ab ~$5/Monat (Basic Tier)

**Konfiguration:** Die Bereitstellung verwendet das Repository `homeadmin24/hausman` auf GitHub.

---

### Option 3: Ohne Docker (Manuelle Installation)

1. Repository klonen:
   ```bash
   git clone https://github.com/homeadmin24/hausman.git
   cd hausman
   ```

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

5. Systemkonfiguration laden:
   ```bash
   php bin/console doctrine:fixtures:load --group=system-config
   ```

6. Admin-Benutzer erstellen:
   ```bash
   php bin/console app:create-admin
   ```

7. Frontend-Assets erstellen:
   ```bash
   npm run build
   ```

8. Entwicklungsserver starten:
   ```bash
   symfony server:start
   ```

**Voraussetzungen:**
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+
- Symfony CLI (optional)
