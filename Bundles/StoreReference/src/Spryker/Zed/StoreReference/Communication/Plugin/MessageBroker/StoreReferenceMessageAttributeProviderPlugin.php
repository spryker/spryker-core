<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;

/**
 * @method \Spryker\Zed\StoreReference\StoreReferenceConfig getConfig()
 * @method \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface getFacade()
 */
class StoreReferenceMessageAttributeProviderPlugin extends AbstractPlugin implements MessageAttributeProviderPluginInterface
{
    /**
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
        try {
            $storeTransfer = $this->getFacade()->getCurrentStore();
        } catch (StoreReferenceNotFoundException $exception) {
            return $messageAttributesTransfer;
        }

        $storeReference = $storeTransfer->getStoreReference();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $emitter = $messageAttributesTransfer->getEmitter() ?? $storeReference;
        $messageAttributesTransfer->setEmitter($emitter);

        return $messageAttributesTransfer;
    }
}
