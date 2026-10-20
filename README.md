## Starten

1. Repository klonen:
```bash
   git clone https://github.com/Alexandro1810/MGIN_05_Praxissystem.git
   cd MGIN_05_Praxissystem
```
2. `.env` aus der Vorlage erstellen:
```powershell
   Copy-Item .env.example .env
```
3. Container starten:
```bash
   docker compose up -d --build
```
4. Im Browser öffnen: http://localhost:8080