<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AssetStorage\Mapper;

use Generated\Shared\Transfer\AssetStorageCollectionTransfer;

interface AssetStorageMapperInterface
{
    /**
     * @param array $assetStorageTransferData
     *
     * @return \Generated\Shared\Transfer\AssetStorageCollectionTransfer
     */
    public function mapAssetStorageDataToAssetStorageTransfer(
        array $assetStorageTransferData
    ): AssetStorageCollectionTransfer;
}
