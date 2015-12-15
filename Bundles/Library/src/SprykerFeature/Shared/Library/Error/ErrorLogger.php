<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Error;

use Spryker\Shared\Lumberjack\Model\Event;
use Spryker\Shared\Lumberjack\Model\EventInterface;
use Spryker\Shared\Lumberjack\Model\EventJournalInterface;
use Spryker\Shared\Lumberjack\Model\SharedEventJournal;
use Spryker\Shared\Library\Application\Version;
use Spryker\Shared\Library\Log;
use Spryker\Shared\NewRelic\Api;
use Spryker\Shared\NewRelic\ApiInterface;

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
    protected static function addDeploymentInformation(EventInterface $event)
    {
        $deploymentInformation = (new Version())->toArray();
        foreach ($deploymentInformation as $name => $data) {
            if (!empty($data)) {
                $event->addField('deployment_' . $name, $data);
            }
        }
    }

}
