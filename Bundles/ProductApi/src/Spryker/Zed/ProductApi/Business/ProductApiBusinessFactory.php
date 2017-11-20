<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductApi\Business\Mapper\EntityMapper;
use Spryker\Zed\ProductApi\Business\Mapper\TransferMapper;
use Spryker\Zed\ProductApi\Business\Model\ProductApi;
use Spryker\Zed\ProductApi\Business\Model\Validator\ProductApiValidator;
use Spryker\Zed\ProductApi\ProductApiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductApi\ProductApiConfig getConfig()
 * @method \Spryker\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface getQueryContainer()
 */
class ProductApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductApi\Business\Model\ProductApiInterface
     */
    public function createProductApi()
    {
        return new ProductApi(
            $this->getApiQueryContainer(),
            $this->getApiQueryBuilderQueryContainer(),
            $this->getQueryContainer(),
            $this->createEntityMapper(),
            $this->createTransferMapper(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductApi\Business\Mapper\EntityMapperInterface
     */
    public function createEntityMapper()
    {
        return new EntityMapper();
    }

    /**
     * @return \Spryker\Zed\ProductApi\Business\Mapper\TransferMapperInterface
     */
    public function createTransferMapper()
    {
        return new TransferMapper();
    }

    /**
     * @return \Spryker\Zed\ProductApi\Business\Model\Validator\ProductApiValidatorInterface
     */
    public function createProductApiValidator()
    {
        return new ProductApiValidator();
    }

    /**
     * @return \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiInterface
     */
    protected function getApiQueryContainer()
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return \Spryker\Zed\ProductApi\Dependency\QueryContainer\ProductApiToApiQueryBuilderInterface
     */
    protected function getApiQueryBuilderQueryContainer()
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::QUERY_CONTAINER_API_QUERY_BUILDER);
    }

    /**
     * @return \Spryker\Zed\ProductApi\Dependency\Facade\ProductApiToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductApiDependencyProvider::FACADE_PRODUCT);
    }
}
