<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Client\Lumberjack;

use Spryker\Shared\Lumberjack\Model\EventInterface;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 */
interface EventJournalClientInterface
{

    /**
     * @param \Spryker\Shared\Lumberjack\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

}
