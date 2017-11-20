<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventJournal\Business;

use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @deprecated Use Log bundle instead
 *
 * @method \Spryker\Zed\EventJournal\Business\EventJournalFactory getFactory()
 */
class EventJournalFacade extends AbstractFacade implements EventJournalFacadeInterface
{
    /**
     * @api
     *
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getFactory()
             ->createEventJournal()
             ->saveEvent($event);
    }
}
