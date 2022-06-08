<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerExtension\Dependency\Plugin;

use Generated\Shared\Transfer\MessageAttributesTransfer;

/**
 * Use this plugin to decorate messages. Properties from the MessageDecorator need to be initialized during construction.
 *
 * Initialized properties will be available for further processing.
 */
interface MessageAttributeProviderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function provideMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer;
}
