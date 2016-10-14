<?php

namespace QUI\QueueManager;

use QUI;

/**
 * Class QueueJob
 *
 * One job that starts a specific worker
 *
 * @package quiqqer/queuemanager
 */
class QueueJob extends QUI\QDOM
{
    /**
     * Job data that is used by workers
     *
     * @var mixed
     */
    protected $data = null;

    /**
     * Class of Worker that executes the job
     *
     * @var string
     */
    protected $workerClass = null;

    /**
     * QueueJob constructor.
     *
     * @param mixed $data (optional) - Additional data the job needs for execution
     * @param string $workerClass - class of Worker that executes the job
     * @param array $attributes - job attributes
     */
    public function __construct($workerClass, $data = null, $attributes = array())
    {
        $this->setAttributes(array(
            'priority'       => 1,
            'deleteOnFinish' => false
        ));

        $this->setAttributes($attributes);

        $this->data        = $data;
        $this->workerClass = $workerClass;
    }

    /**
     * Get class of Worker that executes the job
     *
     * @return string
     */
    public function getWorkerClass()
    {
        return $this->workerClass;
    }

    /**
     * Get job data
     *
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Execute the job
     *
     * @return integer - job ID
     */
    public function queue()
    {
        return QueueManager::addJob($this);
    }
}