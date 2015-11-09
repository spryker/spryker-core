<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Client\Lumberjack\Service;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

interface EventJournalClientInterface
{

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event);

}
