<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\EventJournal\Business;

use SprykerEngine\Shared\EventJournal\Model\EventInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method EventJournalDependencyContainer getDependencyContainer()
 */
class EventJournalFacade extends AbstractFacade
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getDependencyContainer()
             ->createEventJournal()
             ->saveEvent($event);
    }

}
