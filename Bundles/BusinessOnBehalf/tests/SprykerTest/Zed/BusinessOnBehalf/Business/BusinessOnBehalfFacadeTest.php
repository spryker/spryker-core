<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\BusinessOnBehalf\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group BusinessOnBehalf
 * @group Business
 * @group Facade
 * @group BusinessOnBehalfFacadeTest
 * Add your own group annotations below this line
 */
class BusinessOnBehalfFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\BusinessOnBehalf\BusinessOnBehalfBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer
     */
    protected $activeCompany;

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer
     */
    protected $inactiveCompany;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customer = $this->tester->haveCustomer([CustomerTransfer::IS_ON_BEHALF => null]);
        $this->activeCompany = $this->tester->haveActiveCompany();
        $this->inactiveCompany = $this->tester->haveInactiveCompany();
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsOnBehalf(): void
    {
        //Arrange
        $expectedCustomer = clone $this->customer;
        $expectedCustomer->setIsOnBehalf(true);

        $companyUserSeed = [
            CompanyUserTransfer::FK_COMPANY => $this->activeCompany->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customer,
        ];
        $this->haveCompanyUsers(2, $companyUserSeed);

        //Act
        $actualCustomer = $this->tester->getFacade()->expandCustomerWithIsOnBehalf($this->customer);

        //Assert
        $this->tester->assertInstanceOf(CustomerTransfer::class, $actualCustomer);
        $this->tester->assertTransferEquals($expectedCustomer, $actualCustomer);
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsOnBehalfHavingNoCompanyUsers(): void
    {
        //Arrange
        $expectedCustomer = clone $this->customer;
        $expectedCustomer->setIsOnBehalf(false);

        //Act
        $actualCustomer = $this->tester->getFacade()->expandCustomerWithIsOnBehalf($this->customer);

        //Assert
        $this->tester->assertInstanceOf(CustomerTransfer::class, $actualCustomer);
        $this->tester->assertTransferEquals($actualCustomer, $expectedCustomer);
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUsersByCustomerId(): void
    {
        //Arrange
        $expectedCompanyUserAmount = 1;
        $activeCompany = $this->tester->haveActiveCompany();
        $inactiveCompany = $this->tester->haveInactiveCompany();

        $seedDataWithActiveCompany = [
            CompanyUserTransfer::FK_COMPANY => $activeCompany->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customer,
        ];
        $seedDataWithInactiveCompany = [
            CompanyUserTransfer::FK_COMPANY => $inactiveCompany->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customer,
        ];
        $this->tester->haveCompanyUser($seedDataWithActiveCompany);
        $this->tester->haveCompanyUser($seedDataWithInactiveCompany);

        //Act
        $actualCompanyUserCollection = $this->tester->getFacade()->findActiveCompanyUsersByCustomerId($this->customer);

        //Assert
        $this->tester->assertInstanceOf(CompanyUserCollectionTransfer::class, $actualCompanyUserCollection);
        $this->tester->assertCount($expectedCompanyUserAmount, $actualCompanyUserCollection->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testSetDefaultCompanyUserPersistsData(): void
    {
        //Arrange
        $company = $this->tester->haveCompany(['isActive' => true]);
        $seedDataWithCompany = [
            'fkCompany' => $company->getIdCompany(),
            'customer' => $this->customer,
        ];
        $companyUser = $this->tester->haveCompanyUser($seedDataWithCompany);
        $companyUser->setCustomer($this->customer);

        //Act
        $this->tester->getFacade()->setDefaultCompanyUser($companyUser);
        $companyUserFromDataBase = $this->getCompanyUserFacade()->getCompanyUserById($companyUser->getIdCompanyUser());

        //Assert
        $this->assertTrue($companyUserFromDataBase->getIsDefault());
    }

    /**
     * @return void
     */
    public function testSetDefaultCompanyUserToCustomerIfCompanyUserDefault(): void
    {
        //Arrange
        $company = $this->tester->haveCompany(['isActive' => true]);
        $seedData = [
            'isDefault' => true,
            'fkCompany' => $company->getIdCompany(),
            'customer' => $this->customer,
        ];
        $this->tester->haveCompanyUser($seedData);

        //Act
        $customerTransfer = $this->tester->getFacade()->setDefaultCompanyUserToCustomer($this->customer);

        //Assert
        $this->assertSame(
            $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
        );
    }

    /**
     * @return void
     */
    public function testSetDefaultCompanyUserToCustomerIfCompanyUserNotDefault(): void
    {
        //Arrange
        $company = $this->tester->haveCompany(['isActive' => true]);
        $seedData = [
            'isDefault' => false,
            'fkCompany' => $company->getIdCompany(),
            'customer' => $this->customer,
        ];
        $this->tester->haveCompanyUser($seedData);

        //Act
        $customerTransfer = $this->tester->getFacade()->setDefaultCompanyUserToCustomer($this->customer);

        //Assert
        $this->assertNull($customerTransfer->getCompanyUserTransfer());
    }

    /**
     * @return void
     */
    public function testUnsetDefaultCompanyUserByCustomer(): void
    {
        //Arrange
        $company = $this->tester->haveCompany(['isActive' => true]);
        $seedData = [
            'isDefault' => true,
            'fkCompany' => $company->getIdCompany(),
            'customer' => $this->customer,
        ];
        $testDefaultCompanyUser = $this->tester->haveCompanyUser($seedData);
        $seedData = [
            'fkCompany' => $company->getIdCompany(),
            'customer' => $this->customer,
        ];
        $testCompanyUser = $this->tester->haveCompanyUser($seedData);

        //Act
        $this->tester->getFacade()->unsetDefaultCompanyUserByCustomer($this->customer);
        $defaultCompanyUserFromDataBase = $this->getCompanyUserFacade()->getCompanyUserById($testDefaultCompanyUser->getIdCompanyUser());
        $companyUserFromDataBase = $this->getCompanyUserFacade()->getCompanyUserById($testCompanyUser->getIdCompanyUser());

        //Assert
        $this->assertFalse($defaultCompanyUserFromDataBase->getIsDefault());
        $this->assertFalse($companyUserFromDataBase->getIsDefault());
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->tester->getLocator()->companyUser()->facade();
    }

    /**
     * @param int $amount
     * @param array $seed
     *
     * @return array
     */
    protected function haveCompanyUsers(int $amount, array $seed = [])
    {
        $companyUsers = [];

        for ($i = 0; $i < $amount; $i++) {
            $companyUsers[] = $this->tester->haveCompanyUser($seed);
        }

        return $companyUsers;
    }
}
