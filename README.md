# WP Woo Custom Functions (v2.0)

> [!ATTENZIONE]
> **AGGIORNAMENTO DALLA VERSIONE 1.0**: 
> Questa versione introduce una nuova architettura di sicurezza. Se stai aggiornando, il tuo vecchio codice non verrà perso ma non sarà più eseguito automaticamente dal file principale. 
> **Cosa fare:** Prima di aggiornare, copia il tuo codice dal vecchio file `wp-woo-custom-functions.php`. Dopo l'aggiornamento, incollalo nel nuovo editor all'interno della dashboard e clicca su SALVA.

**WP Woo Custom Functions** è un plugin per WordPress progettato per offrire un ambiente di sviluppo sicuro e centralizzato. Consente di inserire snippet di codice PHP, hook di WooCommerce e personalizzazioni del core senza la necessità di creare un tema child o modificare file via FTP.

A differenza di altri manager di snippet, questa versione 2.0 introduce un'architettura **"Safe-Save"** che protegge il tuo sito dai crash (White Screen of Death).

## Novità della Versione 2.0

- **Safe-Save System (Loopback Test)**: Prima di confermare il salvataggio, il plugin effettua un test automatico di stabilità. Se il codice causa un errore fatale, il salvataggio viene bloccato e il sito rimane online.
- **Architettura Ibrida (File + DB)**: Il codice viene eseguito tramite un file fisico (`custom.php`) per massime prestazioni (compatibile con OPcache), ma viene salvato contemporaneamente in una tabella dedicata del Database come backup di emergenza.
- **Editor Professionale Integrato**: Sfrutta la potenza di **CodeMirror** (lo stesso editor di WordPress) con evidenziazione della sintassi, controllo degli errori in tempo reale e numeri di riga.
- **Emergency Recovery**: Un tasto dedicato permette di ripristinare istantaneamente il codice dal Database nel caso in cui il file fisico venga accidentalmente cancellato o corrotto.
- **Interfaccia Responsive**: Editor dinamico che si adatta all'altezza dello schermo per un'esperienza di sviluppo fluida.

## Caratteristiche Principali

- **Indipendenza dal Tema**: Le tue funzioni rimangono attive anche se cambi tema o aggiorni quello attuale.
- **Zero Overhead**: In fase di navigazione (frontend), il plugin non interroga il database. Esegue semplicemente un `include_once` del file fisico.
- **Controllo Totale**: Ideale per sviluppatori che vogliono un unico posto dove gestire la logica del sito senza la frammentazione di mille plugin diversi.

## Installazione

1. Carica la cartella `wp-woo-custom-functions` nella directory `/wp-content/plugins/`.
2. Attiva il plugin tramite il menu 'Plugin' di WordPress.
3. Clicca sulla voce **WPWoo CF** nella barra laterale dell'amministratore.
4. Inizia a scrivere il tuo codice e premi "SALVA".

## Sicurezza (PHP 8.4 Ready)

Il plugin è stato testato e ottimizzato per le versioni più recenti di PHP (fino alla 8.4) e WordPress. Grazie al sistema di test temporaneo, riduce drasticamente il rischio di mandare il sito offline durante le modifiche live.

## Licenza

Distribuito sotto licenza GPLv2 o successiva.