<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\Asset\Communication\Plugin\MessageBroker\AssetMessageHandlerPlugin} instead.
 *
 * @method \Spryker\Zed\Asset\Communication\AssetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Asset\Business\AssetFacadeInterface getFacade()
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 */
class AssetUpdatedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return void
     */
    public function onAssetUpdated(AssetUpdatedTransfer $assetUpdatedTransfer): void
    {
        $this->getFacade()->updateAsset($assetUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield AssetUpdatedTransfer::class => [$this, 'onAssetUpdated'];
    }
}
