<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerEngine\Shared\Lumberjack\Model\Event;
use SprykerEngine\Shared\Lumberjack\Model\EventInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method LumberjackDependencyContainer getDependencyContainer()
 */
class LumberjackFacade extends AbstractFacade
{

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event)
    {
        $this->createEventJournal()->saveEvent($event);
    }

    /**
     * @param array $fields
     */
    public function logEvent(array $fields) {
        $this->saveEvent(
            (new Event())->addFields($fields)
        );
    }

}
