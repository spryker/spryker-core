<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Error;

use SprykerEngine\Shared\EventJournal\Model\Event;
use SprykerEngine\Shared\EventJournal\Model\EventInterface;
use SprykerEngine\Shared\EventJournal\Model\EventJournalInterface;
use SprykerEngine\Shared\EventJournal\Model\SharedEventJournal;
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
        self::sendExceptionToEventJournal($exception, new SharedEventJournal(), $newRelicApi);
    }

    /**
     * @param \Exception $exception
     * @param bool $ignoreInternalExceptions
     * @param EventJournalInterface $eventJournal
     * @param ApiInterface $newRelicApi
     *
     * @return void
     */
    protected static function sendExceptionToEventJournal(
        \Exception $exception,
        EventJournalInterface $eventJournal,
        ApiInterface $newRelicApi,
        $ignoreInternalExceptions = false
    ) {
        try {
            $event = new Event();
            $event->setField('message', $exception->getMessage());
            $event->setField('trace', $exception->getTraceAsString());
            $event->setField('class_name', get_class($exception));
            $event->setField('file_name', $exception->getFile());
            $event->setField('line', $exception->getLine());
            $event->setField(Event::FIELD_NAME, 'exception');
            self::addDeploymentInformation($event);
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
                self::sendExceptionToEventJournal($internalException, $eventJournal, $newRelicApi, true);
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
            self::sendExceptionToEventJournal($internalException, $eventJournal, $newRelicApi, true);
            self::sendExceptionToNewRelic($internalException, $eventJournal, $newRelicApi, true);
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    protected static function addDeploymentInformation(EventInterface $event)
    {
        $deploymentInformation = (new Version())->toArray();
        foreach ($deploymentInformation as $name => $data) {
            if (!empty($data)) {
                $event->setField('deployment_' . $name, $data);
            }
        }
    }

}
