<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerExtension\Dependency\Plugin;

/**
 * Use this plugin to filter the MessageBroker channels.
 */
interface FilterMessageChannelPluginInterface
{
    /**
     * Specification:
     * - Filters the message channels by name.
     *
     * @api
     *
     * @param list<string> $messageChannelNames
     *
     * @return list<string>
     */
    public function filter(array $messageChannelNames): array;
}
