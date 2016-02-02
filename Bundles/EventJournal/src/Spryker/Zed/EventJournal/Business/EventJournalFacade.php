<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method EventJournalFactory getFactory()
 */
class EventJournalFacade extends AbstractFacade
{

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getFactory()
             ->createEventJournal()
             ->saveEvent($event);
    }

}
