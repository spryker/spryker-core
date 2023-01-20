<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\AssetStorage\AssetStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\AssetStorage\Communication\AssetStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetStorage\AssetStorageConfig getConfig()
 */
class AssetPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Asset\Persistence\Map\SpyAssetTableMap::COL_ID_ASSET
     *
     * @var string
     */
    protected const COL_ID_ASSET = 'spy_asset.id_asset';

    /**
     * {@inheritDoc}
     * - Retrieves collection of assets by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\AssetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $assetCriteriaTransfer = $this->createAssetCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getAssetFacade()->getAssetCollection($assetCriteriaTransfer)->getAssets()->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return AssetStorageConfig::ASSET_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return AssetStorageConfig::ASSET_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_ASSET;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\AssetCriteriaTransfer
     */
    protected function createAssetCriteriaTransfer(int $offset, int $limit): AssetCriteriaTransfer
    {
        return (new AssetCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit($limit)->setOffset($offset),
            );
    }
}
