<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Client\Kernel\AbstractFactory;

class LumberjackDependencyContainer extends AbstractFactory
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournalClient()
    {
        return new EventJournalClient();
    }

}
