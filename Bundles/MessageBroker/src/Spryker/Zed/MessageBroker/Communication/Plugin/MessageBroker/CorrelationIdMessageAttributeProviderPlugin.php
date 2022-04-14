<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class CorrelationIdMessageAttributeProviderPlugin extends AbstractPlugin implements MessageAttributeProviderPluginInterface
{
    /**
     * Only set the correlationId when it's not already present.
     *
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function provideMessageAttributes(MessageAttributesTransfer $messageAttributesTransfer): MessageAttributesTransfer
    {
        if ($messageAttributesTransfer->getCorrelationId()) {
            return $messageAttributesTransfer;
        }

        $messageAttributesTransfer->setCorrelationId(Uuid::uuid4()->toString());

        return $messageAttributesTransfer;
    }
}
