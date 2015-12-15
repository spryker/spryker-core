<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Client\Kernel\AbstractDependencyContainer;

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
