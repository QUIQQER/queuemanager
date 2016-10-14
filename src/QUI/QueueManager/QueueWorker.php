<?php

namespace QUI\QueueManager;

use QUI\QueueManager\Exceptions\JobException;
use QUI\QueueManager\Interfaces\IQueueWorker;

/**
 * Class QueueWorker
 *
 * A job worker executes a job based on its job data
 *
 * @package quiqqer/queuemanager
 */
abstract class QueueWorker implements IQueueWorker
{
    /**
     * Job data (necessary for job execution)
     *
     * @var mixed
     */
    protected $data = null;

    /**
     * QueueServerWorker constructor.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Return fully qualified class name
     *
     * @return string
     */
    public static function getClass()
    {
        return '\\' . static::class;
    }

    /**
     * Execute job
     *
     * @return mixed
     */
    abstract function execute();
}