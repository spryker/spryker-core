<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Helper\Plugin;

use Generated\Shared\Transfer\IncomingMessageTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

class IncomingMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\IncomingMessageTransfer $incomingMessageTransfer
     *
     * @return void
     */
    public function handle(IncomingMessageTransfer $incomingMessageTransfer): void
    {
        $foo = 'bar';
    }

    /**
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield IncomingMessageTransfer::class => [$this, 'handle'];
    }
}
