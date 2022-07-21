<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Business;

use Generated\Shared\Transfer\AssetAddedTransfer;
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
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetTransfer
    {
        return $this->getFactory()->createAssetCreator()->addAsset($assetAddedTransfer);
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
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetTransfer
    {
        return $this->getFactory()->createAssetUpdater()->updateAsset($assetUpdatedTransfer);
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
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $this->getFactory()->createAssetDeleter()->deleteAsset($assetDeletedTransfer);
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
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        return $this->getRepository()->findAssetById($idAsset);
    }
}
