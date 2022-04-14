<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Plugin;

use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

class SomethingHappenedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\MessageBrokerTestMessageTransfer $messageBrokerTestMessageTransfer
     *
     * @return void
     */
    public function handle(MessageBrokerTestMessageTransfer $messageBrokerTestMessageTransfer): void
    {
        $foo = 'bar';
    }

    /**
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield MessageBrokerTestMessageTransfer::class => [$this, 'handle'];
    }
}
