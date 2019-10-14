<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapper;
use Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface getRepository()
 */
class CmsSlotBlockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery
     */
    public function getCmsSLotBlockQuery(): SpyCmsSlotBlockQuery
    {
        return SpyCmsSlotBlockQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapperInterface
     */
    public function createCmsSlotBlockMapper(): CmsSlotBlockMapperInterface
    {
        return new CmsSlotBlockMapper();
    }
}
