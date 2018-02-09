<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business;

use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressReader;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressReaderInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressWriter;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressWriterInterface;
use Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressDependencyProvider;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\CompanyUnitAddressPropelRepository;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\CompanyUnitAddressWriterPropelRepository;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyUnitAddressBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressWriterInterface
     */
    public function createCompanyUnitAddressWriter(): CompanyUnitAddressWriterInterface
    {
        return new CompanyUnitAddressWriter(
            $this->createCompanyUnitAddressWriterRepository(),
            $this->getCountryFacade(),
            $this->getLocaleFacade(),
            $this->getCompanyBusinessUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressReaderInterface
     */
    public function createCompanyUnitAddressReader(): CompanyUnitAddressReaderInterface
    {
        return new CompanyUnitAddressReader($this->createCompanyUnitAddressRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    public function createCompanyUnitAddressRepository(): CompanyUnitAddressRepositoryInterface
    {
        return new CompanyUnitAddressPropelRepository();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface
     */
    public function createCompanyUnitAddressWriterRepository(): CompanyUnitAddressWriterRepositoryInterface
    {
        return new CompanyUnitAddressWriterPropelRepository();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade(): CompanyUnitAddressToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface
     */
    protected function getCountryFacade(): CompanyUnitAddressToCountryFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): CompanyUnitAddressToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::FACADE_LOCALE);
    }
}
