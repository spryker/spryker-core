<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\EventJournal\Service;

use Generated\Client\Ide\FactoryAutoCompletion\EventJournalService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method EventJournalService getFactory()
 */
class EventJournalDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
