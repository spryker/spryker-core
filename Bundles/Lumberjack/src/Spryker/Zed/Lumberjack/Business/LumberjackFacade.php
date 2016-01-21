<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\Lumberjack\Business;

use Spryker\Shared\Lumberjack\Model\EventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 *
 * @method LumberjackBusinessFactory getFactory()
 */
class LumberjackFacade extends AbstractFacade
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        trigger_error('Deprecated, will be removed.', E_USER_DEPRECATED);

        $this->getFactory()
             ->createEventJournal()
             ->saveEvent($event);
    }

}
