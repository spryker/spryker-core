<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Error;

use Exception;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Shared\EventJournal\Model\EventJournalInterface;
use Spryker\Shared\EventJournal\Model\SharedEventJournal;
use Spryker\Shared\Library\Application\Version;
use Spryker\Shared\Library\Log;
use Spryker\Shared\NewRelic\Api;
use Spryker\Shared\NewRelic\ApiInterface;

/**
 * @deprecated Use ErrorHandler bundle instead.
 */
class ErrorLogger
{

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    public static function log(Exception $exception)
    {
        $newRelicApi = new Api();

        self::sendExceptionToFile($exception, new SharedEventJournal(), $newRelicApi);
        self::sendExceptionToNewRelic($exception, new SharedEventJournal(), $newRelicApi);
        self::sendExceptionToEventJournal($exception, new SharedEventJournal(), $newRelicApi);
    }

    /**
     * @param \Exception $exception
     * @param \Spryker\Shared\EventJournal\Model\EventJournalInterface $eventJournal
     * @param \Spryker\Shared\NewRelic\ApiInterface $newRelicApi
     * @param bool $ignoreInternalExceptions
     *
     * @return void
     */
    protected static function sendExceptionToEventJournal(
        Exception $exception,
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
     * @param \Spryker\Shared\EventJournal\Model\EventJournalInterface $eventJournal
     * @param \Spryker\Shared\NewRelic\ApiInterface $newRelicApi
     * @param bool $ignoreInternalExceptions
     *
     * @return void
     */
    protected static function sendExceptionToNewRelic(
        Exception $exception,
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
     * @param \Spryker\Shared\EventJournal\Model\EventJournalInterface $eventJournal
     * @param \Spryker\Shared\NewRelic\ApiInterface $newRelicApi
     *
     * @return void
     */
    protected static function sendExceptionToFile(
        Exception $exception,
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
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
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
