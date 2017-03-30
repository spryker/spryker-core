<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Api\Persistence\Mapper\ApiCollectionMapper;
use Spryker\Zed\Api\Persistence\Mapper\ApiItemMapper;
use Spryker\Zed\Api\Persistence\Mapper\FieldMapper;
use Spryker\Zed\Api\Persistence\Mapper\PaginationQueryMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
class ApiPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function createCustomerQuery()
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\PaginationQueryMapperInterface
     */
    public function createPaginationQueryMapper()
    {
        return new PaginationQueryMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\FieldMapperInterface
     */
    public function createFieldMapper()
    {
        return new FieldMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\ApiCollectionMapperInterface
     */
    public function createApiCollectionMapper()
    {
        return new ApiCollectionMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Persistence\Mapper\ApiItemMapperInterface
     */
    public function createApiItemMapper()
    {
        return new ApiItemMapper();
    }

    /**
     * @return \Spryker\Zed\Api\Dependency\QueryContainer\ApiToPropelQueryBuilderInterface
     */
    public function getPropelQueryBuilder()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::QUERY_CONTAINER_PROPEL_QUERY_BUILDER);
    }

}
