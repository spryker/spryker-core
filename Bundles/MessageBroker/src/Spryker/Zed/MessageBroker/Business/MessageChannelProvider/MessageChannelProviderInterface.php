<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageChannelProvider;

use Symfony\Component\Messenger\Envelope;

interface MessageChannelProviderInterface
{
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string
     */
    public function getChannelForMessage(Envelope $envelope): string;
}
