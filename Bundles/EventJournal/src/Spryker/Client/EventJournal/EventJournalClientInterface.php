<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Client\EventJournal;

use Spryker\Shared\EventJournal\Model\EventInterface;

interface EventJournalClientInterface
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

}
