<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Client\Lumberjack\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

/**
 * @method LumberjackDependencyContainer getDependencyContainer()
 */
class LumberjackClient extends AbstractClient
{

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event)
    {
        $this->getDependencyContainer()->createEventJournalClient()->saveEvent($event);
    }

}
