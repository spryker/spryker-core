<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business;

use Spryker\Zed\CompanyUserStorage\Business\Storage\CompanyUserStorageWriter;
use Spryker\Zed\CompanyUserStorage\Business\Storage\CompanyUserStorageWriterInterface;
use Spryker\Zed\CompanyUserStorage\CompanyUserStorageDependencyProvider;
use Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface getRepository()
 */
class CompanyUserStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserStorage\Business\Storage\CompanyUserStorageWriterInterface
     */
    public function createCompanyUserStorageWriter(): CompanyUserStorageWriterInterface
    {
        return new CompanyUserStorageWriter(
            $this->getCompanyUserFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getCompanyUserStorageExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserStorageToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin\CompanyUserStorageExpanderPluginInterface[]
     */
    public function getCompanyUserStorageExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::PLUGINS_COMPANY_USER_STORAGE_EXPANDER);
    }
}
