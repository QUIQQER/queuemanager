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
     * Get status of a job
     *
     * @param integer $jobId
     * @return integer - Status ID
     */
    public static function getJobStatus($jobId);

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

//    /**
//     * Set result of a specific job
//     *
//     * @param integer $jobId
//     * @param array|string $result
//     * @return bool - success
//     *
//     * @throws Exception
//     */
//    public static function setJobResult($jobId, $result);

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
