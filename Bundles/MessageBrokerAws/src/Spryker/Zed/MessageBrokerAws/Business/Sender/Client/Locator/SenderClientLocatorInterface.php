<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sender\Client\Locator;

use Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface;

interface SenderClientLocatorInterface
{
    /**
     * @param string $channelName
     *
     * @return \Spryker\Zed\MessageBrokerAws\Business\Sender\Client\SenderClientInterface
     */
    public function getSenderClientByChannelName(string $channelName): SenderClientInterface;
}
