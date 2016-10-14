<?php

/**
 * Get all available queue servers
 *
 * @return array
 */
QUI::$Ajax->registerFunction(
    'package_quiqqer_queuemanager_ajax_getQueueServers',
    function () {

        ini_set('display_errors', 1);

        // job test
        $Job = new \QUI\QueueManager\QueueJob(
            \QUI\QueueManager\Examples\ExampleWorker::getClass(),
            array(
                'mailBody' => 'Ich bin ein fröhliches Kätzchen.'
            ),
            array(
                'deleteOnFinish' => true
            )
        );

        $Job->queue();

        return \QUI\QueueManager\QueueManager::getAvailableQueueServers();
    },
    array(),
    'Permission::checkAdminUser'
);
