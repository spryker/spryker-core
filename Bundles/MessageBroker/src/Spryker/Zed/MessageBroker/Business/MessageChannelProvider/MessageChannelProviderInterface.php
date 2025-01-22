<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageChannelProvider;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Symfony\Component\Messenger\Envelope;

interface MessageChannelProviderInterface
{
    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string|null
     */
    public function findChannelForMessage(Envelope $envelope): ?string;

    /**
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return list<string>
     */
    public function getChannelsForConsuming(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): array;

    /**
     * @param string $messageName
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException
     *
     * @return string|null
     */
    public function findChannelByMessageName(string $messageName): ?string;
}
