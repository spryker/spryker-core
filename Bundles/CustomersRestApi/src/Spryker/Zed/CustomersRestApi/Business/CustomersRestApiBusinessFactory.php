<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business;

use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriter;
use Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriterInterface;
use Spryker\Zed\CustomersRestApi\Business\Addresses\Mapper\AddressQuoteMapper;
use Spryker\Zed\CustomersRestApi\Business\Addresses\Mapper\AddressQuoteMapperInterface;
use Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapper;
use Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapperInterface;
use Spryker\Zed\CustomersRestApi\Business\Reader\CustomerAddressReader;
use Spryker\Zed\CustomersRestApi\Business\Reader\CustomerAddressReaderInterface;
use Spryker\Zed\CustomersRestApi\Business\Validator\CustomerAddressValidator;
use Spryker\Zed\CustomersRestApi\Business\Validator\CustomerAddressValidatorInterface;
use Spryker\Zed\CustomersRestApi\CustomersRestApiDependencyProvider;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Addresses\AddressesUuidWriterInterface
     */
    public function createCustomersAddressesUuidUpdater(): AddressesUuidWriterInterface
    {
        return new AddressesUuidWriter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Mapper\CustomerQuoteMapperInterface
     */
    public function createCustomerQuoteMapper(): CustomerQuoteMapperInterface
    {
        return new CustomerQuoteMapper($this->getCustomerFacade());
    }

    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Addresses\Mapper\AddressQuoteMapperInterface
     */
    public function createAddressQuoteMapper(): AddressQuoteMapperInterface
    {
        return new AddressQuoteMapper($this->getCustomerFacade());
    }

    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Reader\CustomerAddressReaderInterface
     */
    public function createCustomerAddressReader(): CustomerAddressReaderInterface
    {
        return new CustomerAddressReader($this->getCustomerFacade());
    }

    /**
     * @return \Spryker\Zed\CustomersRestApi\Business\Validator\CustomerAddressValidatorInterface
     */
    public function createCustomerAddressValidator(): CustomerAddressValidatorInterface
    {
        return new CustomerAddressValidator(
            $this->getCustomerFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomersRestApiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CustomersRestApiDependencyProvider::FACADE_CUSTOMER);
    }
}
