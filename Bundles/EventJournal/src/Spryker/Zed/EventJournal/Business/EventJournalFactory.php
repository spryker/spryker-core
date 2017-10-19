<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Zed\EventJournal\Business\Model\EventJournal;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @deprecated Use Log bundle instead
 */
class EventJournalFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\EventJournal\Business\Model\EventJournal
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }
}
