# IT-Prosjekt: NVSG Maskinvare

Nettside hvor man kan registrere spill og maskiner, og se om maskinen din er god nok til å spille ulike spill.

## Hvordan komme i gang

Åpne ```datamodell.mwb``` MySQL Workbench og forward-engineer datamodellen til din lokale server.

Kjør SQL-skriptet ```oppstartsskript.sql```. Dette vil legge inn testdata i databasen.

Start apache og naviger i en nettleser til ```http://localhost/nvsg_maskinvare/``` (eller hvor enn du lagret prosjektet).

Registrer en ny bruker i nettsiden.

Gå inn i MySQL Workbench (eller phpmyadmin) og åpne ```user```-tabellen. Endre din nylig registrerte bruker til admin ved å sette verdien i ```admin```-kolonnen til ```1```.

Gå så tilbake til nettsiden og logg ut.

Logg inn på nytt med brukeren du satte til admin. Naviger til ```nvsg_maskinvare/index.php?page=admin``` 

Det vil da være mulig å registrere platformer og default machines.
