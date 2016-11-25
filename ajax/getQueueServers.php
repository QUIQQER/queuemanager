<?php

/**
 * Get all available queue servers
 *
 * @return array
 */
QUI::$Ajax->registerFunction(
    'package_quiqqer_queuemanager_ajax_getQueueServers',
    function () {
        return \QUI\QueueManager\QueueManager::getAvailableQueueServers();
    },
    array(),
    'Permission::checkAdminUser'
);
