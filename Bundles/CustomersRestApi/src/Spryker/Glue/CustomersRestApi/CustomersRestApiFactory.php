<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToSessionClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReader;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CustomersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToSessionClientInterface
     */
    public function getSessionClient(): CustomerRestApiToSessionClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    public function createCustomerResourceMapper(): CustomersResourceMapperInterface
    {
        return new CustomersResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface
     */
    public function createCustomerReader(): CustomersReaderInterface
    {
        return new CustomersReader(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createCustomerResourceMapper()
        );
    }
}
