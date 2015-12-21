<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\EventJournal;

use Spryker\Client\Kernel\AbstractFactory;

class EventJournalFactory extends  AbstractFactory
{

    /**
     * @return EventJournalClientInterface
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
