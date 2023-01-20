<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Dependency\Facade;

use Generated\Shared\Transfer\AssetCollectionTransfer;
use Generated\Shared\Transfer\AssetCriteriaTransfer;
use Generated\Shared\Transfer\AssetTransfer;

class AssetStorageToAssetFacadeBridge implements AssetStorageToAssetFacadeInterface
{
    /**
     * @var \Spryker\Zed\Asset\Business\AssetFacadeInterface
     */
    protected $assetFacade;

    /**
     * @param \Spryker\Zed\Asset\Business\AssetFacadeInterface $assetFacade
     */
    public function __construct($assetFacade)
    {
        $this->assetFacade = $assetFacade;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        return $this->assetFacade->findAssetById($idAsset);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetCriteriaTransfer $assetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AssetCollectionTransfer
     */
    public function getAssetCollection(AssetCriteriaTransfer $assetCriteriaTransfer): AssetCollectionTransfer
    {
        return $this->assetFacade->getAssetCollection($assetCriteriaTransfer);
    }
}
