<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetStorage;

interface SspAssetStorageEntityMapperInterface
{
    public function mapSspAssetTransferToSspAssetStorageEntity(
        SspAssetTransfer $sspAssetTransfer,
        SpySspAssetStorage $sspAssetStorageEntity
    ): SpySspAssetStorage;
}
