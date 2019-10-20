<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlock\CmsSlotBlockDependencyProvider;
use Spryker\Zed\CmsSlotBlock\Dependency\Service\CmsSlotBlockToUtilEncodingServiceInterface;
use Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsBlockMapper;
use Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsBlockMapperInterface;
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
    public function getCmsSlotBlockQuery(): SpyCmsSlotBlockQuery
    {
        return SpyCmsSlotBlockQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function getCmsBlockQuery(): SpyCmsBlockQuery
    {
        return SpyCmsBlockQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapperInterface
     */
    public function createCmsSlotBlockMapper(): CmsSlotBlockMapperInterface
    {
        return new CmsSlotBlockMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsBlockMapperInterface
     */
    public function createCmsBlockMapper(): CmsBlockMapperInterface
    {
        return new CmsBlockMapper($this->createCmsSlotBlockMapper());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Dependency\Service\CmsSlotBlockToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CmsSlotBlockToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
