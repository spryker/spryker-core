<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business;

use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Expander\CheckoutDataExpander;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Expander\CheckoutDataExpanderInterface;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Mapper\CompanyBusinessUnitAddressQuoteMapper;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Mapper\CompanyBusinessUnitAddressQuoteMapperInterface;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReader;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReaderInterface;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator\CompanyBusinessUnitAddressValidator;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator\CompanyBusinessUnitAddressValidatorInterface;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig getConfig()
 */
class CompanyBusinessUnitAddressesRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Expander\CheckoutDataExpanderInterface
     */
    public function createCheckoutDataExpander(): CheckoutDataExpanderInterface
    {
        return new CheckoutDataExpander($this->getCompanyUnitAddressFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Mapper\CompanyBusinessUnitAddressQuoteMapperInterface
     */
    public function createCompanyBusinessUnitAddressQuoteMapper(): CompanyBusinessUnitAddressQuoteMapperInterface
    {
        return new CompanyBusinessUnitAddressQuoteMapper($this->createCompanyBusinessUnitAddressReader());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReaderInterface
     */
    public function createCompanyBusinessUnitAddressReader(): CompanyBusinessUnitAddressReaderInterface
    {
        return new CompanyBusinessUnitAddressReader($this->getCompanyUnitAddressFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator\CompanyBusinessUnitAddressValidatorInterface
     */
    public function createCompanyBusinessUnitAddressValidator(): CompanyBusinessUnitAddressValidatorInterface
    {
        return new CompanyBusinessUnitAddressValidator(
            $this->getCompanyUnitAddressFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
     */
    public function getCompanyUnitAddressFacade(): CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitAddressesRestApiDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS);
    }
}
