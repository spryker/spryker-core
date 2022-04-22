<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageAttributeProvider;

use Generated\Shared\Transfer\MessageAttributesTransfer;

class MessageAttributeProvider implements MessageAttributeProviderInterface
{
    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface>
     */
    protected array $messageAttributeProviderPlugins = [];

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface> $messageAttributeProviderPlugins
     */
    public function __construct(array $messageAttributeProviderPlugins)
    {
        $this->messageAttributeProviderPlugins = $messageAttributeProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function provideMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        foreach ($this->messageAttributeProviderPlugins as $messageAttributeProviderPlugin) {
            $messageAttributesTransfer = $messageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);
        }

        return $messageAttributesTransfer;
    }
}
