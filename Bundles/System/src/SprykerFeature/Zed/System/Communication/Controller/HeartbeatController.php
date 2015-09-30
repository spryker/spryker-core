<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\System\Communication\Controller;

use SprykerFeature\Shared\Library\Error\ErrorLogger;
/*
 * Class Doctor
 * @package SprykerFeature\Zed\System\Communication\Controller
 */
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class HeartbeatController extends AbstractController
{

    const HEARTBEAT_OK = 'heartbeat:ok';
    const ERROR_EMPTY_DATABASE = 'Empty database';

    public function indexAction()
    {
        try {
            $errors = [];
            $statement = \Propel\Runtime\Propel::getConnection()->query('SHOW TABLES');
            $data = $statement->fetchAll();

            if (count($data) < 10) {
                $errors[] = self::ERROR_EMPTY_DATABASE;
            }

            if (count($errors) === 0) {
                echo self::HEARTBEAT_OK;
            } else {
                $this->printError($errors);
            }
        } catch (\Exception $e) {
            $this->printError([$e->getMessage()]);
            ErrorLogger::log($e);
        }
        die;
    }

    /**
     * Output format in case of error is fixed and parsed for Nagios
     * "<h1>Critical Errors</h1><ul><li>Solr: Is not reachable!</li></ul>
     */
    protected function printError($messages)
    {
        header('HTTP/1.0 503 Service Unavailable');
        echo '<h1>Critical Errors</h1>';

        echo '<ul>';
        foreach ($messages as $message) {
            echo '<li>' . $message . '</li>';
        }
        echo '</ul>';
    }

}
