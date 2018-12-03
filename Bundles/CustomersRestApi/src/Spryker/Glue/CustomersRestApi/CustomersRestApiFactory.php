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
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerForgottenPasswordProcessor;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerForgottenPasswordProcessorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerPasswordWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomerPasswordWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerRestorePasswordResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerRestorePasswordResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessor;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidator;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CustomersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createCustomerResourceMapper(),
            $this->createRestApiErrorProcessor(),
            $this->createRestApiValidator()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customer\CustomerWriterInterface
     */
    public function createCustomerWriter(): CustomerWriterInterface
    {
        return new CustomerWriter(
            $this->getCustomerClient(),
            $this->createCustomerReader(),
            $this->getResourceBuilder(),
            $this->createCustomerResourceMapper(),
            $this->createRestApiErrorProcessor(),
            $this->createRestApiValidator(),
            $this->getCustomerPostRegisterPlugins()
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
            $this->createRestApiErrorProcessor(),
            $this->createRestApiValidator()
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
            $this->createCustomerRestorePasswordResourceMapper(),
            $this->createRestApiErrorProcessor()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\CustomerAddress\CustomerAddressReaderInterface
     */
    public function createCustomerAddressReader(): CustomerAddressReaderInterface
    {
        return new CustomerAddressReader(
            $this->getCustomerClient(),
            $this->createAddressResourceMapper(),
            $this->getResourceBuilder()
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
            $this->createAddressReader(),
            $this->createAddressResourceMapper(),
            $this->createRestApiErrorProcessor(),
            $this->createRestApiValidator()
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
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerRestorePasswordResourceMapperInterface
     */
    public function createCustomerRestorePasswordResourceMapper(): CustomerRestorePasswordResourceMapperInterface
    {
        return new CustomerRestorePasswordResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface
     */
    public function createCustomerResourceMapper(): CustomerResourceMapperInterface
    {
        return new CustomerResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressResourceMapperInterface
     */
    public function createAddressResourceMapper(): AddressResourceMapperInterface
    {
        return new AddressResourceMapper();
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorInterface
     */
    public function createRestApiValidator(): RestApiValidatorInterface
    {
        return new RestApiValidator($this->createRestApiErrorProcessor());
    }

    /**
     * @deprecated Will be removed in the next major.
     *
     * @return \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientInterface
     */
    public function getSessionClient(): CustomersRestApiToSessionClientInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorProcessorInterface
     */
    public function createRestApiErrorProcessor(): RestApiErrorProcessorInterface
    {
        return new RestApiErrorProcessor();
    }

    /**
     * @return \Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostRegisterPluginInterface[]
     */
    public function getCustomerPostRegisterPlugins(): array
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::PLUGINS_CUSTOMER_POST_REGISTER);
    }
}
