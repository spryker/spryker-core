<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetCollectionTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Asset\Business\AssetBusinessFactory getFactory()
 * @method \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface getEntityManager()
 */
class AssetFacade extends AbstractFacade implements AssetFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\AssetFacade::createAsset()} instead.
     *
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer
    {
        return $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchAssetAddedTransferRequest($assetAddedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function createAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer
    {
        return $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchCreateAssetRequest($assetAddedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\AssetFacade::saveAsset()} instead.
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer
    {
        return $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchAssetUpdatedTransferRequest($assetUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function saveAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer
    {
        return $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchSaveAssetRequest($assetUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\AssetFacade::removeAsset()} instead.
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchAssetDeletedTransferRequest($assetDeletedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function removeAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $this->getFactory()
            ->createAssetRequestDispatcher()
            ->dispatchRemoveAssetRequest($assetDeletedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Asset\Business\AssetFacade::getAssetCollection()} instead.
     *
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        return $this->getRepository()->findAssetById($idAsset);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetCriteriaTransfer $assetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetCollectionTransfer
     */
    public function getAssetCollection(AssetCriteriaTransfer $assetCriteriaTransfer): AssetCollectionTransfer
    {
        return $this->getRepository()->getAssetCollection($assetCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function refreshAllAssetStoreRelations(): void
    {
        $this->getFactory()
            ->createAssetStoreRelationWriter()
            ->refreshAllAssetStoreRelations();
    }
}
