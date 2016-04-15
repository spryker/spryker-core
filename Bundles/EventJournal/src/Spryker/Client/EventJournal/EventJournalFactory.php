<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EventJournal;

use Spryker\Client\Kernel\AbstractFactory;

class EventJournalFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\EventJournal\EventJournalClientInterface
     */
    public function createEventJournal()
    {
        return new EventJournal();
    }

}
