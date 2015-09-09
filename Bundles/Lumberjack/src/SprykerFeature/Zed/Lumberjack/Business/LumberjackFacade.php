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
     * @return Model\Event
     */
    public function createEvent()
    {
        return $this->getDependencyContainer()->createEvent();
    }

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event)
    {
        $this->createEventJournal()->saveEvent($event);
    }

    /**
     * @return Model\EventJournal
     */
    public function createEventJournal()
    {
        return $this->getDependencyContainer()->createEventJournal();
    }

}
