<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Lumberjack;

use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

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
