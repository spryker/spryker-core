<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CompanyUser\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\CompanyUser\Dependency\Facade\CompanyUserToCustomerFacadeBridge;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class CompanyUserHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use BusinessHelperTrait;
    use CustomerDataHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function haveCompanyUser(array $seed = []): CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserBuilder($seed))->build();
        $companyUserTransfer->setIdCompanyUser(null);

        $companyUserTransfer->requireCustomer();

        $companyUserResponseTransfer = $this->createCompanyUserFacade()->create($companyUserTransfer);

        return $companyUserResponseTransfer->getCompanyUser();
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function createCompanyUserFacade(): CompanyUserFacadeInterface
    {
        if ($this->getLocatorHelper()->isProjectNamespaceEnabled()) {
            return $this->getLocator()->companyUser()->facade();
        }

        $this->getBusinessHelper()->mockFactoryMethod(
            'getCustomerFacade',
            new CompanyUserToCustomerFacadeBridge($this->getCustomerDataHelper()->getCustomerFacade()),
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPreSavePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPostSavePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPostCreatePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserHydrationPlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserPreDeletePlugins',
            [],
            'CompanyUser',
        );
        $this->getBusinessHelper()->mockFactoryMethod(
            'getCompanyUserSavePreCheckPlugins',
            [],
            'CompanyUser',
        );

        /** @var \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface $companyUserFacade */
        $companyUserFacade = $this->getBusinessHelper()->getFacade('CompanyUser');
        $this->getLocatorHelper()->addToLocatorCache('companyUser-facade', $companyUserFacade);

        return $companyUserFacade;
    }
}
