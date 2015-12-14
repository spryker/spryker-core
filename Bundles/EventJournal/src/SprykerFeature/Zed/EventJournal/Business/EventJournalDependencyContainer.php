<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\EventJournal\Business;

use SprykerFeature\Zed\EventJournal\Business\Model\EventJournal;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
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
