<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\EventJournal;

use Spryker\Client\Kernel\AbstractDependencyContainer;

class EventJournalDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
