<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUsersRestApi\Business;

use Spryker\Zed\CompanyUsersRestApi\Business\CompanyUser\CompanyUserReader;
use Spryker\Zed\CompanyUsersRestApi\Business\CompanyUser\CompanyUserReaderInterface;
use Spryker\Zed\CompanyUsersRestApi\Business\Customer\CustomerReader;
use Spryker\Zed\CompanyUsersRestApi\Business\Customer\CustomerReaderInterface;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpander;
use Spryker\Zed\CompanyUsersRestApi\Business\Expander\CustomerIdentifierExpanderInterface;
use Spryker\Zed\CompanyUsersRestApi\CompanyUsersRestApiDependencyProvider;
use Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeInterface;
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
     * @return \Spryker\Zed\CompanyUsersRestApi\Business\Customer\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader($this->getCustomerFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): CompanyUsersRestApiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUsersRestApiDependencyProvider::FACADE_CUSTOMER);
    }
}
