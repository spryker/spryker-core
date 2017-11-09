<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Spryker\Zed\CustomerApi\Business\Mapper\EntityMapper;
use Spryker\Zed\CustomerApi\Business\Mapper\TransferMapper;
use Spryker\Zed\CustomerApi\Business\Model\CustomerApi;
use Spryker\Zed\CustomerApi\Business\Model\Validator\CustomerApiValidator;
use Spryker\Zed\CustomerApi\CustomerApiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerApi\CustomerApiConfig getConfig()
 * @method \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface getQueryContainer()
 */
class CustomerApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerApi\Business\Model\CustomerApiInterface
     */
    public function createCustomerApi()
    {
        return new CustomerApi(
            $this->getApiQueryContainer(),
            $this->getApiQueryBuilderQueryContainer(),
            $this->getQueryContainer(),
            $this->createCustomerEntityMapper(),
            $this->createCustomerTransferMapper(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface
     */
    public function createCustomerEntityMapper()
    {
        return new EntityMapper();
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface
     */
    public function createCustomerTransferMapper()
    {
        return new TransferMapper();
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Business\Model\Validator\CustomerApiValidatorInterface
     */
    public function createCustomerApiValidator()
    {
        return new CustomerApiValidator();
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected function getApiQueryContainer()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface
     */
    protected function getApiQueryBuilderQueryContainer()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::QUERY_CONTAINER_API_QUERY_BUILDER);
    }

    /**
     * @return \Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerInterface
     */
    protected function getCustomerFacade()
    {
        return $this->getProvidedDependency(CustomerApiDependencyProvider::FACADE_CUSTOMER);
    }
}
