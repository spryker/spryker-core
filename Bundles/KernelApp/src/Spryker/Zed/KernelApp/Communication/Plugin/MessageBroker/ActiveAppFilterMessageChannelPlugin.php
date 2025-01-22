<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Communication\Plugin\MessageBroker;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\FilterMessageChannelPluginInterface;

/**
 * @method \Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface getFacade()
 * @method \Spryker\Zed\KernelApp\KernelAppConfig getConfig()
 */
class ActiveAppFilterMessageChannelPlugin extends AbstractPlugin implements FilterMessageChannelPluginInterface
{
    /**
     * {@inheritDoc}
     * - Queries the message channels for each active App from the database.
     * - An active App is that has either `active` status or `inactive` during the grace period.
     * - Filters the message channels based on the obtained channel list.
     * - Uses grace period for App configuration from {@link \Spryker\Zed\KernelApp\KernelAppConfig::getAppConfigGracePeriod()}.
     *
     * @api
     *
     * @param list<string> $messageChannelNames
     *
     * @return list<string>
     */
    public function filter(array $messageChannelNames): array
    {
        return $this->getFacade()->filterMessageChannels($messageChannelNames);
    }
}
