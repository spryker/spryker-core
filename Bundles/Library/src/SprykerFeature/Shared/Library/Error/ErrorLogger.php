<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;
use SprykerFeature\Shared\Library\Application\Version;
use SprykerEngine\Shared\Lumberjack\Model\SharedEventJournal;
use SprykerEngine\Shared\Lumberjack\Model\Event;


class ErrorLogger
{

    /**
     * @param \Exception $exception
     */
    public static function log(\Exception $exception)
    {
        self::sendExceptionToFile($exception);
        self::sendExceptionToNewRelic($exception);
        self::sendExceptionToLumberjack($exception);
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     */
    protected static function sendExceptionToLumberjack(\Exception $exception, $ignoreInternalExceptions = false)
    {
        try {
            $lumberjack = new SharedEventJournal();
            $event = new Event();
            $event->addField('message', $exception->getMessage());
            $event->addField('trace', $exception->getTraceAsString());
            $event->addField('className', get_class($exception));
            $event->addField('fileName', $exception->getFile());
            $event->addField('line', $exception->getLine());
            $event->addField('name', 'exception');
            self::addDeploymentInfo($event);
            $lumberjack->saveEvent($event);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToNewRelic($internalException, true);
            }
        }
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     */
    protected static function sendExceptionToNewRelic(\Exception $exception, $ignoreInternalExceptions = false)
    {
        try {
            $message = $message = get_class($exception) . ' - ' . $exception->getMessage() . ' in file "' . $exception->getFile() . '"';
            \SprykerFeature_Shared_Library_NewRelic_Api::getInstance()->noticeError($message, $exception);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToLumberjack($internalException, true);
            }
        }
    }

    protected static function addDeploymentInfo(EventInterface $event)
    {
        $deployData = (new Version())->toArray();
        foreach ($deployData as $name => $data) {
            if (!empty($data)) {
                $event->addField('deployment_' . $name, $data);
            }
        }
    }

    /**
     * @param \Exception $exception
     */
    protected static function sendExceptionToFile(\Exception $exception)
    {
        try {
            $message = ErrorRenderer::renderException($exception);
            \SprykerFeature_Shared_Library_Log::log($message, 'exception.log');
        } catch (\Exception $internalException) {
            self::sendExceptionToLumberjack($internalException, true);
            self::sendExceptionToNewRelic($internalException, true);
        }
    }

}
