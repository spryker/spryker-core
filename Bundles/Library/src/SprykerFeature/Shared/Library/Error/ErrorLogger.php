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
use SprykerFeature\Shared\Library\Log;
use SprykerFeature\Shared\NewRelic\Api;
use SprykerFeature\Shared\NewRelic\ApiInterface;

class ErrorLogger
{

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    public static function log(\Exception $exception)
    {
        $newRelicApi = new Api();

        self::sendExceptionToFile($exception, new SharedEventJournal(), $newRelicApi);
        self::sendExceptionToNewRelic($exception, new SharedEventJournal(), $newRelicApi);
        self::sendExceptionToLumberjack($exception, new SharedEventJournal(), $newRelicApi);
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     *
     * @return void
     */
    protected static function sendExceptionToLumberjack(
        \Exception $exception,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi,
        $ignoreInternalExceptions = false
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
                self::sendExceptionToNewRelic($internalException, $eventJournal, $newRelicApi, true);
            }
        }
    }

    /**
     * @param \Exception $exception
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     * @param bool $ignoreInternalExceptions
     *
     * @return void
     */
    protected static function sendExceptionToNewRelic(
        \Exception $exception,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi,
        $ignoreInternalExceptions = false
    ) {
        try {
            $message = $message = get_class($exception) . ' - ' . $exception->getMessage() . ' in file "' . $exception->getFile() . '"';
            $newRelicApi->noticeError($message, $exception);
        } catch (\Exception $internalException) {
            if (!$ignoreInternalExceptions) {
                self::sendExceptionToLumberjack($internalException, $eventJournal, $newRelicApi, true);
            }
        }
    }

    /**
     * @param \Exception $exception
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     *
     * @return void
     */
    protected static function sendExceptionToFile(
        \Exception $exception,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi
    ) {
        try {
            $message = ErrorRenderer::renderException($exception);
            Log::log($message, 'exception.log');
        } catch (\Exception $internalException) {
            self::sendExceptionToLumberjack($internalException, $eventJournal, $newRelicApi, true);
            self::sendExceptionToNewRelic($internalException, $eventJournal, $newRelicApi, true);
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    protected static function addDeploymentInfo(EventInterface $event)
    {
        $deployData = (new Version())->toArray();
        foreach ($deployData as $name => $data) {
            if (!empty($data)) {
                $event->addField('deployment_' . $name, $data);
            }
        }
    }

}
