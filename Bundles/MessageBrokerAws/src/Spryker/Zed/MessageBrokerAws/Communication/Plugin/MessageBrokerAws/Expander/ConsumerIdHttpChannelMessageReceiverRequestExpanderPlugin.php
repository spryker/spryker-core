<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Communication\Plugin\MessageBrokerAws\Expander;

use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerAwsExtension\Dependency\Plugin\HttpChannelMessageReceiverRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig getConfig()
 * @method \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface getFacade()
 */
class ConsumerIdHttpChannelMessageReceiverRequestExpanderPlugin extends AbstractPlugin implements HttpChannelMessageReceiverRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Sets the `Consumer-Id` header using the value from the module's configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\HttpRequestTransfer $httpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\HttpRequestTransfer
     */
    public function expand(HttpRequestTransfer $httpRequestTransfer): HttpRequestTransfer
    {
        return $httpRequestTransfer->addHeader('Consumer-Id', $this->getConfig()->getConsumerId());
    }
}
