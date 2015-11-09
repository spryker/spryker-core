<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Lumberjack\Service;

use Generated\Client\Ide\FactoryAutoCompletion\LumberjackService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method LumberjackService getFactory()
 */
class LumberjackDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournalClient()
    {
        return $this->getFactory()
            ->createEventJournalClient();
    }

}
