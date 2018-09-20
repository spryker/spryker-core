<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Address\AddressReader;
use Spryker\Glue\CustomersRestApi\Processor\Address\AddressReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Address\AddressWriter;
use Spryker\Glue\CustomersRestApi\Processor\Address\AddressWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReader;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomerAddress\CustomerAddressReader;
use Spryker\Glue\CustomersRestApi\Processor\CustomerAddress\CustomerAddressReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrors;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidators;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CustomersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface
     */
    public function getSessionClient(): CustomersRestApiToSessionClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    public function getCustomerClient(): CustomersRestApiToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createCustomersResourceMapper(),
            $this->createRestApiErrors(),
            $this->createRestApiValidators()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriterInterface
     */
    public function createCustomerWriter(): CustomerWriterInterface
    {
        return new CustomerWriter(
            $this->getCustomerClient(),
            $this->getResourceBuilder(),
            $this->createCustomersResourceMapper(),
            $this->createRestApiErrors(),
            $this->createRestApiValidators()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Address\AddressReaderInterface
     */
    public function createAddressReader(): AddressReaderInterface
    {
        return new AddressReader(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createAddressResourceMapper(),
            $this->createRestApiErrors(),
            $this->createRestApiValidators()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\CustomerAddress\CustomerAddressReaderInterface
     */
    public function createCustomerAddressReader(): CustomerAddressReaderInterface
    {
        return new CustomerAddressReader(
            $this->getCustomerClient(),
            $this->createAddressResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Address\AddressWriterInterface
     */
    public function createAddressWriter(): AddressWriterInterface
    {
        return new AddressWriter(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createAddressResourceMapper(),
            $this->createRestApiErrors(),
            $this->createRestApiValidators()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface
     */
    public function createCustomersResourceMapper(): CustomerResourceMapperInterface
    {
        return new CustomerResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface
     */
    public function createAddressResourceMapper(): AddressResourceMapperInterface
    {
        return new AddressResourceMapper(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface
     */
    public function createRestApiValidators(): RestApiValidatorsInterface
    {
        return new RestApiValidators($this->createRestApiErrors());
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface
     */
    public function createRestApiErrors(): RestApiErrorsInterface
    {
        return new RestApiErrors();
    }
}
