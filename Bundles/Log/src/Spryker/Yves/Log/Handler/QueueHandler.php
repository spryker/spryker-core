<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Handler;

use Monolog\Logger;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Log\Handler\AbstractQueueHandler;

/**
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
class QueueHandler extends AbstractQueueHandler
{
    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @phpstan-param Level|LevelName|\Psr\Log\LogLevel::*|array<Level|LevelName|LogLevel::*> $level
     *
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param string $queueName
     * @param int|string $level Level or level name
     * @param bool $bubble
     */
    public function __construct(QueueClientInterface $queueClient, $queueName, $level = Logger::DEBUG, $bubble = true)
    {
        $this->queueClient = $queueClient;
        $this->queueName = $queueName;

        parent::__construct($level, $bubble);
    }
}
