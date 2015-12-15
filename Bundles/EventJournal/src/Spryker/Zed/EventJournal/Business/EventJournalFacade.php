<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

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
