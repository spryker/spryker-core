<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Receiver\Client;

use Symfony\Component\Messenger\Envelope;

interface ReceiverClientInterface
{
    /**
     * @param string $channelName
     *
     * @return array<\Symfony\Component\Messenger\Envelope>
     */
    public function get(string $channelName): iterable;

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function ack(Envelope $envelope): void;

    /**
     * @param \Symfony\Component\Messenger\Envelope $envelope
     *
     * @return void
     */
    public function reject(Envelope $envelope): void;
}
