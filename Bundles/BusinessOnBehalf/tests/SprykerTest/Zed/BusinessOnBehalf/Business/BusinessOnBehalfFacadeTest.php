<?php

namespace SprykerTest\Zed\BusinessOnBehalf\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
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
        $this->customer = $this->tester->haveCustomer(['isOnBehalf' => null]);
        $this->activeCompany = $this->tester->haveCompany(['isActive' => true]);
        $this->inactiveCompany = $this->tester->haveCompany(['isActive' => false]);
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
            'fkCompany' => $this->activeCompany->getIdCompany(),
            'customer' => $this->customer,
        ];
        $this->tester->haveCompanyUsers(2, $companyUserSeed);

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
        $activeCompany = $this->tester->haveCompany(['isActive' => true]);
        $inactiveCompany = $this->tester->haveCompany(['isActive' => false]);

        $seedDataWithActiveCompany = [
            'fkCompany' => $activeCompany->getIdCompany(),
            'customer' => $this->customer,
        ];
        $seedDataWithInactiveCompany = [
            'fkCompany' => $inactiveCompany->getIdCompany(),
            'customer' => $this->customer,
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
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->tester->getLocator()->companyUser()->facade();
    }
}
