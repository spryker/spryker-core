<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Client\Kernel\AbstractDependencyContainer;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 */
class LumberjackDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournalClient()
    {
        return new EventJournalClient();
    }

}
