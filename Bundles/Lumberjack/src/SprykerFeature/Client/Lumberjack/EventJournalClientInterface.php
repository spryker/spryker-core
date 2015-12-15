<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Client\Lumberjack;

use Spryker\Shared\Lumberjack\Model\EventInterface;

interface EventJournalClientInterface
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

}
