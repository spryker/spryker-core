<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery;
use Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\CmsBlockProductStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStorageQueryContainerInterface getQueryContainer()
 */
class CmsBlockProductStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery
     */
    public function createSpyCmsBlockProductStorageQuery()
    {
        return SpyCmsBlockProductStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductStorage\Dependency\QueryContainer\CmsBlockProductStorageToCmsBlockProductConnectorQueryContainerInterface
     */
    public function getCmsBlockProductConnectorQuery()
    {
        return $this->getProvidedDependency(CmsBlockProductStorageDependencyProvider::QUERY_CONTAINER_CMS_BLOCK_PRODUCT_CONNECTOR);
    }
}
