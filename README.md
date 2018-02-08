QUIQQER Queue Server Manager
========

This plugin enables you to use and implement asynchronous message queues within QUIQQER. Use different implementations of QUIQQER Queue Servers to add jobs to a queue. These jobs are then executed asynchronously, in parallel and do not halt or interfere with your main runtime.

This package in and of itself offers no direct functionality but is required for Queue Server implementations like `quiqqer/queueserver` (included as a requirement) or `quiqqer/rabbitmqserver`.

Package Name:

    quiqqer/queuemanager


Features
--------
* Framework for QUIQQER Queue Server implementations

Installation
------------
The Package Name is: quiqqer/queuemanager

Usage
----------
### General info
Generally, a queue receives messages and outputs them to receivers in a pre-defined manner (in this case FIFO based on priority). A message
is called a "job" in the QUIQQER Queue Server Manager context. Each job is executed by a "Worker". A Worker is a class that reads
job data and can do with it whatever the developer desires.

### Implementation example
```php
$Job = new \QUI\QueueManager\QueueJob(
    \QUI\QueueManager\Examples\ExampleWorker::getClass(),
    array(
        'string' => 'One two three'
    )
);

$jobId = $Job->queue(); // Queue job

// Save $jobId if needed elsewhere (if you want to do something with the job later on)
```

The Example-`Worker` `ExampleWorker` is a simple class that reverses a string

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
        return strrev($string);  // this result is saved in the Job and the Job is then markes as completed
    }
}
```

```php
// fetch job result
$result = \QUI\QueueServer\Server::getJobrResult($job); // "eerht owt enO"
```

Contribute
----------
- Project: https://dev.quiqqer.com/quiqqer/queuemanager
- Issue Tracker: https://dev.quiqqer.com/quiqqer/queuemanager/issues
- Source Code: https://dev.quiqqer.com/quiqqer/queuemanager/tree/master


Support
-------
If you found any errors or have wishes or suggestions for improvement,
you can contact us by email at support@pcsg.de.

We will transfer your message to the responsible developers.

License
-------
GPL-3.0+