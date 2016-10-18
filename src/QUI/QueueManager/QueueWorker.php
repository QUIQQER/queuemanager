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
     * ID of job that is processed
     *
     * @var integer
     */
    protected $jobId = null;

    /**
     * Job data (necessary for job execution)
     *
     * @var mixed
     */
    protected $data = null;

    /**
     * QueueWorker constructor.
     *
     * @param integer $jobId - Job ID
     * @param mixed $data
     */
    public function __construct($jobId, $data)
    {
        $this->jobId = $jobId;
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
     * Write log entry for the job that is currenty processed
     *
     * @param $msg
     */
    public function writeLogEntry($msg)
    {
        QueueManager::writeJobLogEntry($this->jobId, $msg);
    }

    /**
     * Execute job
     *
     * @return mixed
     */
    abstract function execute();
}