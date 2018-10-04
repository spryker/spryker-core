<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CustomersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriterInterface
     */
    public function createCustomersWriter(): CustomersWriterInterface
    {
        return new CustomersWriter(
            $this->getCustomerClient(),
            $this->getResourceBuilder(),
            $this->createCustomersResourceMapper(),
            $this->getCustomerRegisterPostSavePlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CustomersRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    public function createCustomersResourceMapper(): CustomersResourceMapperInterface
    {
        return new CustomersResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface
     */
    public function getSessionClient(): CustomersRestApiToSessionClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostRegisterPluginInterface[]
     */
    public function getCustomerRegisterPostSavePlugins(): array
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::PLUGINS_CUSTOMER_POST_REGISTER);
    }
}
