<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Lumberjack\Service;

use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

class LumberjackDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournalClient()
    {
        return new EventJournalClient();
    }

}
