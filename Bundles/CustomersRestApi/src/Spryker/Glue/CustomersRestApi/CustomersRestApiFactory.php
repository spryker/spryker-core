<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerForgottenPasswordProcessor;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerForgottenPasswordProcessorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerPasswordWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerPasswordWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResetPasswordResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResetPasswordResourceMapperInterface;
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
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerForgottenPasswordProcessorInterface
     */
    public function createCustomerForgottenPasswordProcessor(): CustomerForgottenPasswordProcessorInterface
    {
        return new CustomerForgottenPasswordProcessor(
            $this->getCustomerClient(),
            $this->getResourceBuilder(),
            $this->createCustomerForgottenPasswordResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerPasswordWriterInterface
     */
    public function createCustomerPasswordWriter(): CustomerPasswordWriterInterface
    {
        return new CustomerPasswordWriter(
            $this->getCustomerClient(),
            $this->getResourceBuilder(),
            $this->createCustomerResetPasswordResourceMapper()
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
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface
     */
    public function createCustomerForgottenPasswordResourceMapper(): CustomerForgottenPasswordResourceMapperInterface
    {
        return new CustomerForgottenPasswordResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResetPasswordResourceMapperInterface
     */
    public function createCustomerResetPasswordResourceMapper(): CustomerResetPasswordResourceMapperInterface
    {
        return new CustomerResetPasswordResourceMapper();
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
}
