<?php

namespace QUI\QueueManager\Interfaces;

interface IQueueWorker
{
    /**
     * Execute job
     *
     * @return mixed
     */
    function execute();

    /**
     * Return fully qualified class name
     *
     * @return string
     */
    static function getClass();
}
