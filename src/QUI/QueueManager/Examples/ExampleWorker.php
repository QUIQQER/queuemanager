<?php

namespace QUI\QueueManager\Examples;

use QUI\QueueManager\QueueManager;
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
     * @return mixed
     */
    public function execute()
    {
        $this->writeLogEntry('String came in: ' . $this->data['string']);
        $string = $this->data['string'];
        $this->writeLogEntry('String reversed.');
        return strrev($string);
    }
}