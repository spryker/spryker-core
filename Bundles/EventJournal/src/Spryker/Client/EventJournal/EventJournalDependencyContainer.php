<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\EventJournal;

use Generated\Client\Ide\FactoryAutoCompletion\EventJournalService;
use Spryker\Client\Kernel\AbstractServiceDependencyContainer;

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
