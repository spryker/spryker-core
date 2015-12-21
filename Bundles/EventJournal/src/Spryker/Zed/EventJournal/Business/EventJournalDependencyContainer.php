<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Zed\EventJournal\Business\Model\EventJournal;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\EventJournalBusiness;

/**
 * @method EventJournalBusiness getFactory
 */
class EventJournalDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Model\EventJournal
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
