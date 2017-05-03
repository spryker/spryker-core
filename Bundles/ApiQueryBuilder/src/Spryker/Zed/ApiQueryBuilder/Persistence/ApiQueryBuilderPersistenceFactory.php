<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiQueryBuilder\Persistence;

use Spryker\Zed\ApiQueryBuilder\ApiQueryBuilderDependencyProvider;
use Spryker\Zed\ApiQueryBuilder\Persistence\Mapper\ApiRequestMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ApiQueryBuilder\ApiQueryBuilderConfig getConfig()
 * @method \Spryker\Zed\ApiQueryBuilder\Persistence\ApiQueryBuilderQueryContainer getQueryContainer()
 */
class ApiQueryBuilderPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\ApiQueryBuilder\Persistence\Mapper\ApiRequestMapperInterface
     */
    public function createApiRequestMapper()
    {
        return new ApiRequestMapper(
            $this->getPropelQueryBuilderQueryContainer(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToApiInterface
     */
    protected function getApiQueryContainer()
    {
        return $this->getProvidedDependency(ApiQueryBuilderDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return \Spryker\Zed\ApiQueryBuilder\Dependency\QueryContainer\ApiQueryBuilderToPropelQueryBuilderInterface
     */
    protected function getPropelQueryBuilderQueryContainer()
    {
        return $this->getProvidedDependency(ApiQueryBuilderDependencyProvider::QUERY_CONTAINER_PROPEL_QUERY_BUILDER);
    }

    /**
     * @return \Spryker\Zed\ApiQueryBuilder\Dependency\Service\ApiQueryBuilderToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ApiQueryBuilderDependencyProvider::SERVICE_UTIL_ENCODING);
    }

}
