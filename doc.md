# DOCUMENTS PERSONNEL SUR PHP (CODEIGNITER):

## Configuation generale:


### Configuration de la base de donnees:
1) fichier.env:
### bCreer a la racine du projet le fichier .env et mettre ceci dedans :
```txt
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
app.indexPage = ''
# SQLite pour aller vite ; tout autre pilote convient (Postgre, SQLSRV...)
database.default.DBDriver = SQLite3
database.default.database = biblio.db
```

### Remarque:
    Adapter selon la base de donnees: POstgreSql , MySql , etc ...


