<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence\Mapper;

use Generated\Shared\Transfer\CmsSlotStorageTransfer;
use Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage;

class CmsSlotStorageMapper implements CmsSlotStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotStorageTransfer $cmsSlotStorageTransfer
     * @param \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage $cmsSlotStorageEntity
     *
     * @return \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage
     */
    public function mapCmsSlotStorageTransferToEntity(
        CmsSlotStorageTransfer $cmsSlotStorageTransfer,
        SpyCmsSlotStorage $cmsSlotStorageEntity
    ): SpyCmsSlotStorage {
        $cmsSlotStorageEntity->setKey($cmsSlotStorageTransfer->getKey());
        $cmsSlotStorageEntity->setData($cmsSlotStorageTransfer->toArray());

        return $cmsSlotStorageEntity;
    }
}
