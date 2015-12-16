<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Monolog;

use Monolog\Handler\AbstractHandler;
use Spryker\Shared\EventJournal\Model\Event;
use Spryker\Shared\EventJournal\Model\SharedEventJournal;

class EventJournalHandler extends AbstractHandler
{

    const MESSAGE_LOG_MONOLOG = 'monolog';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function handle(array $record)
    {
        $journal = new SharedEventJournal();
        $event = new Event();
        $event->setField('extra', $record['extra']);
        $event->setField('context', $record['context']);
        $event->setField('channel', $record['channel']);
        $event->setField('context', $record['context']);
        $event->setField('message', $record['message']);
        $event->setField('level', $record['level_name']);
        $event->setField('name', self::MESSAGE_LOG_MONOLOG);
        $journal->saveEvent($event);
    }

}
