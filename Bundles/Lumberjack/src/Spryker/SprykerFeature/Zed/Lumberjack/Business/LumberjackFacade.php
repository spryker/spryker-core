<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Zed\Lumberjack\Business;

use Spryker\Shared\Lumberjack\Model\EventInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method LumberjackDependencyContainer getDependencyContainer()
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
        $this->getDependencyContainer()
             ->createEventJournal()
             ->saveEvent($event);
    }

}
