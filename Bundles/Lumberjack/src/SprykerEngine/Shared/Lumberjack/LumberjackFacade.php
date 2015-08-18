<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Shared\Lumberjack;

use SprykerFeature\Shared\Lumberjack\Persistence;

class LumberjackFacade
{

    static function createEvent(array $data = [])
    {
        $entry = new EventTuple();
        $entry->addFields($data);

        return $entry;
    }

    static function logEntry(EntryInterface $entry)
    {

    }
}

/*
$logEntry = LumberjackFacade::createEntry('PaymentAPICall');
$logEntry->addField('order_id', 1);
$logEntry->startTimer();
$logEntry->addCheckpoint('authorize');
$logEntry->addField('authorize_request', $payment->getRequestAsArray());
$payment->authorize();
$logEntry->addField('authorize_response', $payment->getResponseAsArray());
$logEntry->addCheckpoint('capture');
$logEntry->addField('capture_request', $payment->getRequestAsArray());
$payment->capture();
$logEntry->addField('capture_response', $payment->getResponseAsArray());
$logEntry->stop(); // optional here, implicitly called on ::log
LumberjackFacade::logEntry($logEntry);
*/




