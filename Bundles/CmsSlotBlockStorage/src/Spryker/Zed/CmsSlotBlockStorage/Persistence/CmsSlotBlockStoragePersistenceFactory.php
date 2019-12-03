<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Persistence;

use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorageQuery;
use Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageDependencyProvider;
use Spryker\Zed\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToUtilEncodingServiceInterface;
use Spryker\Zed\CmsSlotBlockStorage\Persistence\Propel\Mapper\CmsSlotBlockStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface getRepository()
 */
class CmsSlotBlockStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorageQuery
     */
    public function createCmsSlotBlockStorageQuery(): SpyCmsSlotBlockStorageQuery
    {
        return SpyCmsSlotBlockStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery
     */
    public function createCmsSlotBlockQuery(): SpyCmsSlotBlockQuery
    {
        return SpyCmsSlotBlockQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockStorage\Persistence\Propel\Mapper\CmsSlotBlockStorageMapper
     */
    public function createCmsSlotBlockStorageMapper(): CmsSlotBlockStorageMapper
    {
        return new CmsSlotBlockStorageMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockStorage\Dependency\Service\CmsSlotBlockStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): CmsSlotBlockStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
