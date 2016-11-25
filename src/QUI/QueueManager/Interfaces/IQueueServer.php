<?php

namespace QUI\QueueManager\Interfaces;

use QUI\Exception;
use QUI\QueueManager\QueueJob;

interface IQueueServer
{
    const JOB_STATUS_QUEUED   = 1;
    const JOB_STATUS_RUNNING  = 2;
    const JOB_STATUS_FINISHED = 3;
    const JOB_STATUS_ERROR    = 4;

    /**
     * Adds a single job to the queue of a server
     *
     * @param QueueJob $Job - The job to add to the queue
     * @return integer - unique Job ID
     */
    public static function queueJob(QueueJob $Job);

    /**
     * Clone a job and queue it immediately
     *
     * @param integer $jobId - Job ID
     * @param integer $priority - (new) job priority
     * @return integer - ID of cloned job
     */
    public static function cloneJob($jobId, $priority);

    /**
     * Get status of a job
     *
     * @param integer $jobId
     * @return integer - Status ID
     */
    public static function getJobStatus($jobId);

    /**
     * Write log entry for a job
     *
     * @param integer $jobId
     * @param string $msg
     * @return bool - success
     */
    public static function writeJobLogEntry($jobId, $msg);

    /**
     * Get event log for specific job
     *
     * @param integer $jobId
     * @return array
     */
    public static function getJobLog($jobId);

    /**
     * Get result of a specific job
     *
     * @param integer $jobId
     * @param bool $deleteJob (optional) - delete job from queue after return [default: true]
     * @return array
     *
     * @throws Exception
     */
    public static function getJobResult($jobId, $deleteJob = true);

    /**
     * Close server connection
     */
    public static function closeConnection();

    /**
     * Delete a job
     *
     * @param integer - $jobId
     * @return bool - success
     */
    public static function deleteJob($jobId);

//    /**
//     * Set status of a job
//     *
//     * @param integer $jobId
//     * @param integer $status
//     * @return bool - success
//     */
//    public static function setJobStatus($jobId, $status);
}
