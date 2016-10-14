queuemanager
========

Stellt das Grundgerüst für Queue Server Jobs und Worker bereit

Paketname:

    quiqqer/queuemanager


Features (Funktionen)
--------
Automatisierte, asynchrone, verteilte Ausführung von Code

Installation
------------

Der Paketname ist: quiqqer/queuemanager


Mitwirken
----------

- Issue Tracker: 
- Source Code: 


Support
-------

Falls Sie einen Fehler gefunden haben oder Verbesserungen wünschen,
senden Sie bitte eine E-Mail an support@pcsg.de.


Lizenz
-------


Entwickler
--------

Patrick Müller (p.mueller@pcsg.de)

Beispiel
--------

### Allgemeines

Die Queue ist eine Art Warteschlange für auszuführende Aufgaben, die **asynchron** ausgeführt werden. Jede Aufgabe (`QueueJob`) wird von einem `QueueWorker` ausgeführt. Ein `QueueWorker`
ist eine PHP-Klasse, die die Schnittstelle `IQueueWorker` implementiert und beliebigen PHP-Code ausführt. Für die Verwaltung und Distribution
 von `QueueJobs` an passende `QueueWorker` ist der `QueueServer` verantwortlich. `QueueServer` werden von separaten QUIQQER-Modulen
 bereitgestellt. Sie müssen die Schnittstelle `IQueueServer` implementieren.

### Einen neuen Job ausführen und in die Queue senden

Dieses Beispiel erstellt einen neuen `QueueJob` und reiht ihn in die Warteschlange ein. Die übergebene Klasse des `Workers`
muss aus dem absoluten Namespace (mit anführendem `\ `) bestehen. Dadurch weiß der `QueueServer` an welchen `Worker` er die Daten
des Jobs senden muss.

```php
$Job = new \QUI\QueueManager\QueueJob(
    \QUI\QueueManager\Examples\ExampleWorker::getClass(),
    array(
        'string' => 'Eins zwei drei vier'
    )
);

$jobId = $Job->queue(); // Job in die Warteschlange einreihen

// Speichere $jobId an geeigneter Stelle
```

Der Beispiel-`Worker` `ExampleWorker` ist eine einfache Klasse, die einen String umkehrt:

```php
namespace QUI\QueueManager\Examples;

use QUI\QueueManager\QueueWorker;

/**
 * Class ExampleWorker
 *
 * Example worker that reverses a string
 *
 * @package quiqqer/queuemanager
 */
class ExampleWorker extends QueueWorker
{
    /**
     * Execute job
     *
     * @return string
     */
    public function execute()
    {
        $string = $this->data['string'];
        return strrev($string);
    }
}
```

Wann der `QueueServer` den `QueueJob` an einen passenden `QueueWorker` verteilt hat und wann dieser die Aufgabe erledigt hat,
ist unbekannt. Daher muss in regelmäßigen Abständen gefragt werden, ob der `QueueJob` erledigt ist bzw. ob ein Ergebnis vorliegt.
Nicht alle `QueueJobs` produzieren jedoch Ergebnisse, die wieder abgerufen werden können. Manche erledigen einfach nur ihre Aufgabe und sind
dann ohne weitere Rückmeldung fertig (z.B. ein `QueueWorker` der eine E-Mail versendet).

```php
    $jobStatus = \QUI\QueueManager\QueueManager::getJobStatus($jobId);
    
    // Status kann sein:
    // 1 - Job ist in der Queue und noch nicht bearbeitet
    // 2 - Job wird gerade bearbeitet
    // 3 - Job wurde erfolgreich bearbeitet
    // 4 - Bei der Bearbeitung des Jobs ist ein (unerwarteter) Fehler aufgetreten - in diesem Fall bitte die Log-Dateien ansehen
    
    $jobResult = \QUI\QueueManager\QueueManager::getJobResult($jobId);
    
    // enthält den umgekehrten String oder wirft eine Exception, wenn der Job noch in Bearbeitung ist oder noch nicht bearbeitet wurde
```