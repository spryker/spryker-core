<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
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
            $this->createCustomersResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected function getCustomerClient(): CustomersRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER_CLIENT);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected function createCustomersResourceMapper(): CustomersResourceMapperInterface
    {
        return new CustomersResourceMapper(
            $this->getResourceBuilder()
        );
    }
}
