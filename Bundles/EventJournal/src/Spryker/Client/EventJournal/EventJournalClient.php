<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EventJournal;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\EventJournal\Model\EventInterface;

/**
 * @deprecated Use Log bundle instead
 *
 * @method \Spryker\Client\EventJournal\EventJournalFactory getFactory()
 */
class EventJournalClient extends AbstractClient implements EventJournalClientInterface
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
        $this->getFactory()->createEventJournal()->saveEvent($event);
    }

}
