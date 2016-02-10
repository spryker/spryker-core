<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Shared\EventJournal\Model\EventInterface;

interface EventJournalFacadeInterface
{

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

}
