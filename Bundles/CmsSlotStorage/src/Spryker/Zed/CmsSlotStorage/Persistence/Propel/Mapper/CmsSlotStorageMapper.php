<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage;

class CmsSlotStorageMapper implements CmsSlotStorageMapperInterface
{
    protected const KEY_STORAGE_DATA_KEY = 'key';
    protected const KEY_STORAGE_DATA_CONTENT_PROVIDER_TYPE = 'content_provider_type';
    protected const KEY_STORAGE_DATA_NAME = 'name';
    protected const KEY_STORAGE_DATA_DESCRIPTION = 'description';

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     * @param \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage $cmsSlotStorageEntity
     *
     * @return \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorage
     */
    public function mapCmsSlotTransferToStorageEntity(
        CmsSlotTransfer $cmsSlotTransfer,
        SpyCmsSlotStorage $cmsSlotStorageEntity
    ): SpyCmsSlotStorage {
        $cmsSlotStorageData = $cmsSlotTransfer->toArray();

        $cmsSlotStorageEntity->setKey($cmsSlotTransfer->getKey());
        $cmsSlotStorageEntity->setData([
            static::KEY_STORAGE_DATA_KEY => $cmsSlotStorageData[static::KEY_STORAGE_DATA_KEY],
            static::KEY_STORAGE_DATA_CONTENT_PROVIDER_TYPE => $cmsSlotStorageData[static::KEY_STORAGE_DATA_CONTENT_PROVIDER_TYPE],
            static::KEY_STORAGE_DATA_NAME => $cmsSlotStorageData[static::KEY_STORAGE_DATA_NAME],
            static::KEY_STORAGE_DATA_DESCRIPTION => $cmsSlotStorageData[static::KEY_STORAGE_DATA_DESCRIPTION],
        ]);

        return $cmsSlotStorageEntity;
    }
}
