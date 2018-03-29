<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business;

use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReader;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressWriter;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressWriterInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddress;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressInterface;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutor;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface;
use Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressDependencyProvider;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToCountryFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Dependency\Facade\CompanyUnitAddressToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 */
class CompanyUnitAddressBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressInterface
     */
    public function createCompanyUnitAddress(): CompanyUnitAddressInterface
    {
        return new CompanyUnitAddress(
            $this->getEntityManager(),
            $this->getCountryFacade(),
            $this->getLocaleFacade(),
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyUnitAddressPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressWriterInterface
     */
    public function createCompanyBusinessUnitAddressWriter(): CompanyBusinessUnitAddressWriterInterface
    {
        return new CompanyBusinessUnitAddressWriter(
            $this->createCompanyBusinessUnitAddressReader(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface
     */
    public function createCompanyBusinessUnitAddressReader(): CompanyBusinessUnitAddressReaderInterface
    {
        return new CompanyBusinessUnitAddressReader(
            $this->getRepository(),
            $this->createCompanyUnitAddressPluginExecutor()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface
     */
    protected function createCompanyUnitAddressPluginExecutor(): CompanyUnitAddressPluginExecutorInterface
    {
        return new CompanyUnitAddressPluginExecutor(
            $this->getCompanyUnitAddressHydratePlugins(),
            $this->getCompanyUnitAddressPostSavePlugins()
        );
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

    /**
     * @return \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface[]
     */
    protected function getCompanyUnitAddressPostSavePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::PLUGIN_ADDRESS_POST_SAVE);
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface[]
     */
    protected function getCompanyUnitAddressHydratePlugins(): array
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::PLUGIN_ADDRESS_TRANSFER_HYDRATING);
    }
}
