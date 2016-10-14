<?php

namespace QUI\QueueManager\Examples;

use QUI\Mail\Mailer;
use QUI\QueueManager\QueueWorker;

/**
 * Class QueueWorker
 *
 * A job worker executes a job based on its job data
 *
 * @package quiqqer/queuemanager
 */
class ExampleWorker extends QueueWorker
{
    /**
     * Execute job
     *
     * @return mixed
     */
    public function execute()
    {
        $Mail = new Mailer(array(
            'MAILFrom'     => "peat@pcsg.de",
            'MAILFromText' => "My job is done",
            'MAILReplyTo'  => "peat@pcsg.de"
        ));

        $Mail->addRecipient('p.mueller@pcsg.de');
        $Mail->setSubject('Job-Queue test');
        $Mail->setBody($this->data['mailBody']);

        return array(
            'mailSent' => $Mail->send()
        );
    }
}