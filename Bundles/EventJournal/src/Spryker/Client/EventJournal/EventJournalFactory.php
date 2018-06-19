<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EventJournal;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\EventJournal\Model\EventJournalInterface;

/**
 * @deprecated Use Log bundle instead
 */
class EventJournalFactory extends AbstractFactory
{
    /**
     * @return EventJournalInterface
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }
}
