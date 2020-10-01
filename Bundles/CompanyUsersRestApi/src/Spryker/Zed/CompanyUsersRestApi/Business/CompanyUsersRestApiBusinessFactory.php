<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business;

use Spryker\Zed\CompanyUsersRestApi\Business\CompanyUser\CompanyUserReader;
use Spryker\Zed\CompanyUsersRestApi\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CompanyUserExpander;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CompanyUserExpanderInterface;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpanderInterface;
use Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiDependencyProvider;
use Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 * @method \Spryker\Zed\CompanyUsersRestApi\Persistence\CompanyUsersRestApiRepositoryInterface getRepository()
 */
class CompanyUsersRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpanderInterface
     */
    public function createCustomerIdentifierExpander(): CustomerIdentifierExpanderInterface
    {
        return new CustomerIdentifierExpander();
    }

    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Business\CompanyUser\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Business\Expander\CompanyUserExpanderInterface
     */
    public function createCompanyUserExpander(): CompanyUserExpanderInterface
    {
        return new CompanyUserExpander($this->getCompanyUserFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUsersRestApiToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::FACADE_COMPANY_USER);
    }
}
