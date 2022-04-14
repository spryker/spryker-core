<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\Locator;

use Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface;

interface ReceiverClientLocatorInterface
{
    /**
     * @param string $channelName
     *
     * @return \Spryker\Zed\MessageBrokerAws\Business\Receiver\Client\ReceiverClientInterface
     */
    public function getReceiverClientByChannelName(string $channelName): ReceiverClientInterface;
}
