<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Lumberjack\Model\EventInterface;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 *
 * @method LumberjackFactory getFactory()
 */
class LumberjackClient extends AbstractClient
{

    /**
     * @param \Spryker\Shared\Lumberjack\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getFactory()->createEventJournalClient()->saveEvent($event);
    }

}
