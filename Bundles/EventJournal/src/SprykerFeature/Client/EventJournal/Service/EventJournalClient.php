<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Client\EventJournal\Service;

use SprykerEngine\Client\Kernel\AbstractClient;
use SprykerEngine\Shared\EventJournal\Model\EventInterface;

/**
 * @method EventJournalDependencyContainer getDependencyContainer()
 */
class EventJournalClient extends AbstractClient implements EventJournalClientInterface
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getDependencyContainer()->createEventJournal()->saveEvent($event);
    }

}
