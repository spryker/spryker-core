<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Client\Kernel\AbstractFactory;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 */
class LumberjackFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Lumberjack\EventJournalClientInterface
     */
    public function createEventJournalClient()
    {
        return new EventJournalClient();
    }

}
