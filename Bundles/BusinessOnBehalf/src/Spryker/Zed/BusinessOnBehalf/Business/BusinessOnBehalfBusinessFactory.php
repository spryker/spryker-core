<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Business;

use Spryker\Zed\BusinessOnBehalf\Business\Model\CompanyUserCollectionFinder;
use Spryker\Zed\BusinessOnBehalf\Business\Model\CompanyUserCollectionFinderInterface;
use Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpander;
use Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpanderInterface;
use Spryker\Zed\BusinessOnBehalf\Business\Model\IsDefaultCompanyUserUpdater;
use Spryker\Zed\BusinessOnBehalf\BusinessOnBehalfDependencyProvider;
use Spryker\Zed\BusinessOnBehalf\Dependency\Facade\CompanyUserToBusinessOnBehalfFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface getRepository()
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\BusinessOnBehalf\BusinessOnBehalfConfig getConfig()
 */
class BusinessOnBehalfBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Business\Model\CustomerExpanderInterface
     */
    public function createCustomerExpander(): CustomerExpanderInterface
    {
        return new CustomerExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Business\Model\CompanyUserCollectionFinderInterface
     */
    public function createCompanyUserCollectionFinder(): CompanyUserCollectionFinderInterface
    {
        return new CompanyUserCollectionFinder(
            $this->getRepository(),
            $this->getCompanyUserFacade()
        );
    }

    public function createIsDefaultCompanyUserUpdater()
    {
        return new IsDefaultCompanyUserUpdater(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Dependency\Facade\CompanyUserToBusinessOnBehalfFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserToBusinessOnBehalfFacadeInterface
    {
        return $this->getProvidedDependency(BusinessOnBehalfDependencyProvider::FACADE_COMPANY_USER);
    }
}
