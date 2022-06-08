<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetStorage\Dependency\Facade;

use Generated\Shared\Transfer\AssetTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
class AssetStorageToAssetBridge implements AssetStorageToAssetInterface
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
     * @param int $idAsset
     *
     * @return \Generated\Shared\Transfer\AssetTransfer|null
     */
    public function findAssetById(int $idAsset): ?AssetTransfer
    {
        return $this->assetFacade->findAssetById($idAsset);
    }
}
