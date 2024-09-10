<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Queue;

use Codeception\Actor;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\RabbitMqConsumerOptionTransfer;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class QueueBusinessTester extends Actor
{
    use _generated\QueueBusinessTesterActions;

    /**
     * @var string
     */
    protected const RABBITMQ = 'rabbitmq';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @return array
     */
    public function getQueueReceiverOptions(): array
    {
        $queueOptionTransfer = new RabbitMqConsumerOptionTransfer();
        $queueOptionTransfer->setConsumerExclusive(false);
        $queueOptionTransfer->setNoWait(false);
        $queueOptionTransfer->setRequeueOnReject(true);

        return [
            static::RABBITMQ => $queueOptionTransfer,
        ];
    }

    /**
     * @param array<string> $queueNames
     *
     * @return array<\Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface>
     */
    public function getMessageProcessorPlugins(array $queueNames): array
    {
        $plugins = [];

        foreach ($queueNames as $queueName) {
            $plugins[$queueName] = new EventQueueMessageProcessorPlugin();
        }

        return $plugins;
    }

    /**
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function buildSendMessageTransfer(): QueueSendMessageTransfer
    {
        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->setBody(json_encode([
            'write' => [
                'key' => 'testKey',
                'value' => 'testValue',
                'resource' => 'testResource',
                'params' => [],
            ],
        ]));
        $queueSendMessageTransfer->setStoreName(static::STORE_NAME_DE);

        return $queueSendMessageTransfer;
    }

    /**
     * @return string
     */
    public function getCommandSignature(): string
    {
        return APPLICATION_VENDOR_DIR . 'bin/console queue:task:start';
    }

    /**
     * @return string
     */
    public function getServerName(): string
    {
        return gethostname() ?: php_uname('n');
    }
}
