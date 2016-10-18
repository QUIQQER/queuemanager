<?php

/**
 * Get all available queue servers
 *
 * @return array
 */
QUI::$Ajax->registerFunction(
    'package_quiqqer_queuemanager_ajax_getQueueServers',
    function () {
        // job test
        $Job = new \QUI\QueueManager\QueueJob(
            \QUI\QueueManager\Examples\ExampleWorker::getClass(),
            array(
                'string' => 'Ich bin ein fröhliches Honigkuchenkaninchen.'
            )
        );

        $Job->queue();

        return \QUI\QueueManager\QueueManager::getAvailableQueueServers();
    },
    array(),
    'Permission::checkAdminUser'
);
