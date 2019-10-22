<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi;

use Spryker\Glue\CustomerAccessRestApi\Dependency\Client\CustomerAccessRestApiToCustomerAccessStorageClientInterface;
use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessReader;
use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessReaderInterface;
use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestFormatter;
use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestFormatterInterface;
use Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapper;
use Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapperInterface;
use Spryker\Glue\CustomerAccessRestApi\Processor\RestResponseBuilder\CustomerAccessRestResponseBuilder;
use Spryker\Glue\CustomerAccessRestApi\Processor\RestResponseBuilder\CustomerAccessRestResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig getConfig()
 */
class CustomerAccessRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestFormatterInterface
     */
    public function createCustomerAccessRequestFormatter(): CustomerAccessRequestFormatterInterface
    {
        return new CustomerAccessRequestFormatter(
            $this->getCustomerAccessStorageClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessReaderInterface
     */
    public function createCustomerAccessReader(): CustomerAccessReaderInterface
    {
        return new CustomerAccessReader(
            $this->getConfig(),
            $this->getCustomerAccessStorageClient(),
            $this->createCustomerAccessRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapperInterface
     */
    public function createCustomerAccessMapper(): CustomerAccessMapperInterface
    {
        return new CustomerAccessMapper();
    }

    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Processor\RestResponseBuilder\CustomerAccessRestResponseBuilderInterface
     */
    public function createCustomerAccessRestResponseBuilder(): CustomerAccessRestResponseBuilderInterface
    {
        return new CustomerAccessRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createCustomerAccessMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Dependency\Client\CustomerAccessRestApiToCustomerAccessStorageClientInterface
     */
    public function getCustomerAccessStorageClient(): CustomerAccessRestApiToCustomerAccessStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerAccessRestApiDependencyProvider::CLIENT_CUSTOMER_ACCESS_STORAGE);
    }
}
