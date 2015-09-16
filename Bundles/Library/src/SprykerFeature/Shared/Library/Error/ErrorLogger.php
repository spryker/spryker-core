<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Error;

use SprykerEngine\Shared\Lumberjack\Model\Event;
use SprykerEngine\Shared\Lumberjack\Model\EventInterface;
use SprykerEngine\Shared\Lumberjack\Model\EventJournalInterface;
use SprykerEngine\Shared\Lumberjack\Model\SharedEventJournal;
use SprykerFeature\Shared\Library\Application\Version;
use SprykerFeature\Shared\Library\NewRelic\Api;
use SprykerFeature\Shared\Library\NewRelic\ApiInterface;

class ErrorLogger
{

    /**
     * @param \Exception $exception
     */
    public static function log(\Exception $exception)
    {
        self::sendExceptionToFile($exception, new SharedEventJournal(), Api::getInstance());
        self::sendExceptionToNewRelic($exception, false, new SharedEventJournal(), Api::getInstance());
        self::sendExceptionToLumberjack($exception, false, new SharedEventJournal(), Api::getInstance());
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     */
    protected static function sendExceptionToLumberjack(
        \Exception $exception,
        $ignoreInternalExceptions = false,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi
    ) {
        try {
            $event = new Event();
            $event->addField('message', $exception->getMessage());
            $event->addField('trace', $exception->getTraceAsString());
            $event->addField('class_name', get_class($exception));
            $event->addField('file_name', $exception->getFile());
            $event->addField('line', $exception->getLine());
            $event->addField(Event::FIELD_NAME, 'exception');
            self::addDeploymentInfo($event);
            $eventJournal->saveEvent($event);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToNewRelic($internalException, true, $eventJournal, $newRelicApi);
            }
        }
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     */
    protected static function sendExceptionToNewRelic(
        \Exception $exception,
        $ignoreInternalExceptions = false,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi
    ) {
        try {
            $message = $message = get_class($exception) . ' - ' . $exception->getMessage() . ' in file "' . $exception->getFile() . '"';
            $newRelicApi->noticeError($message, $exception);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToLumberjack($internalException, true, $eventJournal, $newRelicApi);
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
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     */
    protected static function sendExceptionToFile(
        \Exception $exception,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi
    ) {
        try {
            $message = ErrorRenderer::renderException($exception);
            \SprykerFeature_Shared_Library_Log::log($message, 'exception.log');
        } catch (\Exception $internalException) {
            self::sendExceptionToLumberjack($internalException, true, $eventJournal, $newRelicApi);
            self::sendExceptionToNewRelic($internalException, true, $eventJournal, $newRelicApi);
        }
    }

}
