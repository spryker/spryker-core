<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerExtension\Dependency\Plugin;

use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

interface MessageSenderPluginInterface extends SenderInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getTransportName(): string;
}
