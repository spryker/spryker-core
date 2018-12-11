<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUsersRestApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerIdentifierTransfer;
use Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUsersRestApi
 * @group Business
 * @group Facade
 * @group CompanyUsersRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUsersRestApiFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyUsersRestApi\CompanyUsersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacade
     */
    protected $businessOnBehalfFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customerTransfer = $this->tester->haveCustomer();
        $this->businessOnBehalfFacade = $this->getBusinessOnBehalfFacade();
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhenCustomerWithoutAnyCompany(): void
    {
        // Act
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $this->customerTransfer
        );

        // Assert
        $this->assertEquals(null, $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhenCustomerHasOneCompany(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        $this->customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $this->businessOnBehalfFacade->setDefaultCompanyUserToCustomer($this->customerTransfer);
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $this->customerTransfer
        );

        // Assert
        $this->assertEquals($this->customerTransfer->getCompanyUserTransfer()->getUuid(), $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhenCustomerHasDefaultCompany(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        $secondCompanyTransfer = $this->tester->haveCompany();

        $secondCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $secondCompanyTransfer->getIdCompany(),
        ]);

        $defaultCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $secondCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $secondCompanyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        $this->customerTransfer->setCompanyUserTransfer($defaultCompanyUserTransfer);

        // Act
        $this->businessOnBehalfFacade->setDefaultCompanyUserToCustomer($this->customerTransfer);
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $this->customerTransfer
        );

        // Assert
        $this->assertEquals($defaultCompanyUserTransfer->getUuid(), $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testExpandCustomerIdentifierWhenCustomerWithoutDefaultCompany(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();

        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        $secondCompanyTransfer = $this->tester->haveCompany();

        $secondCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $secondCompanyTransfer->getIdCompany(),
        ]);

        $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $secondCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $secondCompanyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        // Act
        $this->businessOnBehalfFacade->unsetDefaultCompanyUserByCustomer($this->customerTransfer);
        $expandedCustomerIdentifierTransfer = $this->tester->getFacade()->expandCustomerIdentifier(
            (new CustomerIdentifierTransfer()),
            $this->customerTransfer
        );

        // Assert
        $this->assertEquals(null, $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }

    /**
     * @return \Spryker\Zed\BusinessOnBehalf\Business\BusinessOnBehalfFacadeInterface
     */
    protected function getBusinessOnBehalfFacade(): BusinessOnBehalfFacadeInterface
    {
        return $this->tester->getLocator()->businessOnBehalf()->facade();
    }
}
