<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Publisher\Asset;

use Spryker\Shared\Asset\AssetConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 */
class AssetWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Passes through `EventEntity` transfers to get the Assets to publish.
     * - Processes found `Asset` transfers one by one.
     * - Finds all existing AssetSlotStorage entries using `Asset.assetSlot` and Asset.stores`.
     * - Updates the found entries by adding or updating Asset data matching `Asset.idAsset`.
     * - Creates new AssetSlotStorage entry if there's no yet.
     * - Saves change to the database.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeAssetCollectionByAssetEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            AssetConfig::ASSET_PUBLISH,
        ];
    }
}
