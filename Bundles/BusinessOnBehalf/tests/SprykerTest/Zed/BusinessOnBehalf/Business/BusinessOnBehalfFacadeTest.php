<?php

namespace SprykerTest\Zed\BusinessOnBehalf\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

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
}
