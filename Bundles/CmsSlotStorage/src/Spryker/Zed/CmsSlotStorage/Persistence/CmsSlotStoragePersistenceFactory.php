<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Persistence;

use Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorageQuery;
use Spryker\Zed\CmsSlotStorage\Persistence\Propel\Mapper\CmsSlotStorageMapper;
use Spryker\Zed\CmsSlotStorage\Persistence\Propel\Mapper\CmsSlotStorageMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface getEntityManager()
 */
class CmsSlotStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsSlotStorage\Persistence\SpyCmsSlotStorageQuery
     */
    public function getCmsSlotStorageQuery(): SpyCmsSlotStorageQuery
    {
        return SpyCmsSlotStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsSlotStorage\Persistence\Propel\Mapper\CmsSlotStorageMapperInterface
     */
    public function createCmsSlotStorageMapper(): CmsSlotStorageMapperInterface
    {
        return new CmsSlotStorageMapper();
    }
}
