<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Receiver\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class ChannelNameStamp implements StampInterface
{
    /**
     * @var string
     */
    protected string $channelName;

    /**
     * @param string $channelName
     */
    public function __construct(string $channelName)
    {
        $this->channelName = $channelName;
    }

    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }
}
