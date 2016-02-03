<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Zed\EventJournal\Business\Model\EventJournal;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

class EventJournalFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\EventJournal\Business\Model\EventJournal
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
