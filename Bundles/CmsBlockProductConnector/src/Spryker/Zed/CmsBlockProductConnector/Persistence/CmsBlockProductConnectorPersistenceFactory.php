<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery;
use Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorDependencyProvider;
use Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Propel\Mapper\CmsBlockProductConnectorMapper;
use Spryker\Zed\CmsBlockProductConnector\Persistence\Propel\Mapper\CmsBlockProductConnectorMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\CmsBlockProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorRepositoryInterface getRepository()
 */
class CmsBlockProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function createCmsBlockProductConnectorQuery(): SpyCmsBlockProductConnectorQuery
    {
        return SpyCmsBlockProductConnectorQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer\CmsBlockProductConnectorToProductAbstractQueryContainerInterface
     */
    public function getCmsBlockProductConnectorToProductAbstractQueryContainer(): CmsBlockProductConnectorToProductAbstractQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsBlockProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\CmsBlockProductConnector\Persistence\Propel\Mapper\CmsBlockProductConnectorMapperInterface
     */
    public function createCmsBlockProductConnectorMapper(): CmsBlockProductConnectorMapperInterface
    {
        return new CmsBlockProductConnectorMapper();
    }
}
