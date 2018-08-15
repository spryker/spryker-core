<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToSessionClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Addresses\AddressesReader;
use Spryker\Glue\CustomersRestApi\Processor\Addresses\AddressesReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReader;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriter;
use Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriterInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomersAddresses\CustomersAddressesReader;
use Spryker\Glue\CustomersRestApi\Processor\CustomersAddresses\CustomersAddressesReaderInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapper;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface;
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
     * @return \Spryker\Glue\CustomersRestApi\Processor\Mapper\AddressesResourceMapperInterface
     */
    public function createAddressResourceMapper(): AddressesResourceMapperInterface
    {
        return new AddressesResourceMapper(
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

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Addresses\AddressesReaderInterface
     */
    public function createAddressesReader(): AddressesReaderInterface
    {
        return new AddressesReader(
            $this->getResourceBuilder(),
            $this->getCustomerClient(),
            $this->createAddressResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\CustomersAddresses\CustomersAddressesReaderInterface
     */
    public function createCustomersAddressesReader(): CustomersAddressesReaderInterface
    {
        return new CustomersAddressesReader(
            $this->getCustomerClient(),
            $this->createAddressResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersWriterInterface
     */
    public function createCustomersWriter(): CustomersWriterInterface
    {
        return new CustomersWriter(
            $this->getCustomerClient(),
            $this->getResourceBuilder()
        );
    }
}
