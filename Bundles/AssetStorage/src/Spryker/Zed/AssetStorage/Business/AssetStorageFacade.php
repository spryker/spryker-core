<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AssetStorage\Business\AssetStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AssetStorage\Persistence\AssetStorageRepositoryInterface getRepository()
 */
class AssetStorageFacade extends AbstractFacade implements AssetStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeAssetCollectionByAssetEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->writeAssetCollectionByAssetEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function deleteAssetCollectionByAssetEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->deleteAssetCollectionByAssetEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function publish(int $idAsset): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->publish($idAsset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function publishStoreRelation(int $idAsset, int $idStore): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->publishStoreRelation($idAsset, $idStore);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return void
     */
    public function unpublish(int $idAsset): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->unpublish($idAsset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     * @param int $idStore
     *
     * @return void
     */
    public function unpublishStoreRelation(int $idAsset, int $idStore): void
    {
        $this->getFactory()
            ->createAssetStorageWriter()
            ->unpublishStoreRelation($idAsset, $idStore);
    }
}
