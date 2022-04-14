<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Persistence\Mapper;

use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\Asset\Persistence\SpyAsset;

class AssetMapper implements AssetMapperInterface
{
    /**
     * @param \Orm\Zed\Asset\Persistence\SpyAsset $assetEntity
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function mapAssetEntityToAssetTransfer(
        SpyAsset $assetEntity
    ): AssetTransfer {
        return (new AssetTransfer())->fromArray($assetEntity->toArray(), true);
    }
}
