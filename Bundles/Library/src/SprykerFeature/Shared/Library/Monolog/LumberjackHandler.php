<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Monolog;

use Monolog\Handler\AbstractHandler;
use Spryker\Shared\Lumberjack\Model\SharedEventJournal;
use Spryker\Shared\Lumberjack\Model\Event;

class LumberjackHandler extends AbstractHandler
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
        $event->addField('extra', $record['extra']);
        $event->addField('context', $record['context']);
        $event->addField('channel', $record['channel']);
        $event->addField('context', $record['context']);
        $event->addField('message', $record['message']);
        $event->addField('level', $record['level_name']);
        $event->addField('name', self::MESSAGE_LOG_MONOLOG);
        $journal->saveEvent($event);
    }

}
