<?php

namespace QUI\QueueManager;

use QUI;
use QUI\QueueManager\Interfaces\IQueueServer;
use QUI\QueueManager\Exceptions\ServerException;
use QUI\Utils\System\File as QUIFile;
use QUI\Utils\Text\XML;

/**
 * Class QueueManager
 *
 * Manager that adds Job to server queue
 *
 * @package quiqqer/queuemanager
 */
class QueueManager
{
    /**
     * Current queue server that is used for executing jobs
     *
     * @var IQueueServer
     */
    protected static $QueueServer = null;

    /**
     * Add job to job queue
     *
     * @param QueueJob $Job
     * @return integer - Job ID
     *
     * @throws ServerException
     */
    public static function addJob(QueueJob $Job)
    {
        try {
            return self::getQueueServer()->queueJob($Job);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);

            throw new ServerException(
                'exception.queuemanager.addjob.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Get current status of a specific
     *
     * @param integer $jobId
     * @return integer - job status code
     *
     * @throws ServerException
     */
    public static function getJobStatus($jobId)
    {
        try {
            return self::getQueueServer()->getJobStatus($jobId);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.getjobstatus.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Get result of a job (if finished)
     *
     * @param integer $jobId
     * @return mixed
     *
     * @throws ServerException
     */
    public static function getJobResult($jobId)
    {
        try {
            return self::getQueueServer()->getJobResult($jobId);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.getjobresult.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Get log of job
     *
     * @param integer $jobId
     * @return array - job log entries
     *
     * @throws ServerException
     */
    public static function getJobLog($jobId)
    {
        try {
            return self::getQueueServer()->getJobLog($jobId);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.getjoblog.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Write entry for job log
     *
     * @param integer $jobId
     * @param string $msg - log entry message
     * @return bool - success
     *
     * @throws ServerException
     */
    public static function writeJobLogEntry($jobId, $msg)
    {
        try {
            return self::getQueueServer()->writeJobLogEntry($jobId, $msg);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.writejoblogentry.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Cacnel a job
     *
     * @param integer $jobId
     * @return bool - success
     *
     * @throws ServerException
     */
    public static function deleteJob($jobId)
    {
        try {
            return self::getQueueServer()->deleteJob($jobId);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.deletejob.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Get current queue server based on settings
     *
     * @return IQueueServer
     *
     * @throws ServerException
     */
    protected static function getQueueServer()
    {
        if (!is_null(self::$QueueServer)) {
            return self::$QueueServer;
        }

        $Config           = QUI::getPackage('quiqqer/queuemanager')->getConfig();
        $queueServerClass = $Config->get('queue', 'server');

        if (empty($queueServerClass)) {
            throw new ServerException(array(
                'quiqqer/queuemanager',
                'exception.queuemanager.not.queue.server.configured'
            ));
        }

        self::$QueueServer = new $queueServerClass();

        return self::$QueueServer;
    }

    /**
     * Clone a job and queue it immediately
     *
     * @param integer $jobId - ID of job that is to be cloned
     * @param integer $priority - priority of cloned job
     * @return int - cloned job ID
     * @throws ServerException
     */
    public static function cloneJob($jobId, $priority)
    {
        try {
            return self::getQueueServer()->cloneJob($jobId, $priority);
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.clonejob.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Close the current connection to the queue server
     *
     * @return void
     * @throws ServerException
     */
    public static function closeConnection()
    {
        try {
            self::getQueueServer()->closeConnection();
        } catch (\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
            
            throw new ServerException(
                'exception.queuemanager.closeconnection.error',
                array(
                    'error' => $Exception->getMessage()
                )
            );
        }
    }

    /**
     * Get all available queue servers via reading queueserver.xml files from
     * all packages
     *
     * @return array
     *
     * @throws ServerException
     */
    public static function getAvailableQueueServers()
    {
        $queueServers = array();

        if (!defined('OPT_DIR')) {
            throw new ServerException(
                'exception.queuemanager.packages.dir.not.found'
            );
        }

        $packages = QUIFile::readDir(OPT_DIR);
        $L        = QUI::getLocale();

        // then we can read the rest xml files
        foreach ($packages as $package) {
            if ($package == 'composer') {
                continue;
            }

            $package_dir = OPT_DIR . '/' . $package;
            $list        = QUIFile::readDir($package_dir);

            foreach ($list as $sub) {
                if (!is_dir($package_dir . '/' . $sub)) {
                    continue;
                }

                $xmlFile = $package_dir . '/' . $sub . '/queueserver.xml';

                if (!file_exists($xmlFile)) {
                    continue;
                }

                $Dom     = XML::getDomFromXml($xmlFile);
                $servers = $Dom->getElementsByTagName('queueserver');

                /** @var \DOMElement $Servers */
                $Servers = $servers->item(0);
                $items   = $Servers->getElementsByTagName('server');

                if (!$items->length) {
                    continue;
                }

                for ($i = 0; $i < $items->length; $i++) {
                    /** @var \DOMElement $Item */
                    $Item = $items->item($i);

                    if ($Item->nodeName == '#text') {
                        continue;
                    }

                    // title
                    /** @var \DOMElement $Title */
                    $Title  = $Item->getElementsByTagName('title')->item(0);
                    $Locale = $Title->getElementsByTagName('locale')->item(0);

                    $title = $L->get(
                        $Locale->getAttribute('group'),
                        $Locale->getAttribute('var')
                    );

                    // description
                    /** @var \DOMElement $Title */
                    $Title  = $Item->getElementsByTagName('description')->item(0);
                    $Locale = $Title->getElementsByTagName('locale')->item(0);

                    $description = $L->get(
                        $Locale->getAttribute('group'),
                        $Locale->getAttribute('var')
                    );

                    $queueServers[] = array(
                        'class'       => $Item->getAttribute('class'),
                        'title'       => $title,
                        'description' => $description
                    );
                }
            }
        }

        return $queueServers;
    }
}