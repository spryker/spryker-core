<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Client\EventJournal\Service;

use SprykerEngine\Shared\EventJournal\Model\EventInterface;

interface EventJournalClientInterface
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

}
