<?php

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
     * @return mixed
     */
    public function execute()
    {
        $string = $this->data['string'];
        return strrev($string);
    }
}