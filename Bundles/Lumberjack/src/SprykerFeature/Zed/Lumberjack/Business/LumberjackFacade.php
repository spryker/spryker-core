<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

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
