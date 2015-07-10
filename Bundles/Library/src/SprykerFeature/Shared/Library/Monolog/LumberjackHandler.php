<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Monolog;

use Monolog\Handler\AbstractHandler;
use SprykerFeature\Shared\Lumberjack\Code\Lumberjack;

class LumberjackHandler extends AbstractHandler
{

    const MESSAGE_LOG_MONOLOG = 'monolog';

    /**
     * {@inheritdoc}
     */
    public function handle(array $record)
    {
        $lumberjack = Lumberjack::getInstance();
        $lumberjack->addField('extra', $record['extra']);
        $lumberjack->addField('context', $record['context']);
        $lumberjack->addField('channel', $record['channel']);
        $lumberjack->addField('context', $record['context']);
        $lumberjack->send(self::MESSAGE_LOG_MONOLOG, $record['message'], $record['level_name']);
    }

}
