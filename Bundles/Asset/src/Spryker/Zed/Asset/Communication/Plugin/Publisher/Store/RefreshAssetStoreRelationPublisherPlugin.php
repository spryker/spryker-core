<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Communication\Plugin\Publisher\Store;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\Asset\Business\AssetFacadeInterface getFacade()
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 * @method \Spryker\Zed\Asset\Communication\AssetCommunicationFactory getFactory()
 */
class RefreshAssetStoreRelationPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @uses \Spryker\Shared\StoreStorage\StoreStorageConfig::ENTITY_SPY_STORE_CREATE
     *
     * @var string
     */
    protected const ENTITY_SPY_STORE_CREATE = 'Entity.spy_store.create';

    /**
     * {@inheritDoc}
     * - Fetches a collection of assets from the Persistence.
     * - Iterates over the collection and triggers an update for each asset, causing store relation recreation.
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
        $this->getFacade()->refreshAllAssetStoreRelations();
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
            static::ENTITY_SPY_STORE_CREATE,
        ];
    }
}
