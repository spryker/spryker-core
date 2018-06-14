<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyResponseBuilder;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\CompanyUserCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use TypeError;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUser
 * @group Business
 * @group Facade
 * @group CompanyUserFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUserFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyUser\CompanyUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateShouldPersistCompanyUser(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = (new CompanyUserBuilder(['fk_company' => $companyTransfer->getIdCompany()]))->withCustomer()->build();

        // Act
        $companyUserResponseTransfer = $this->getFacade()->create($companyUserTransfer);
        $foundCompanyUserTransfer = $this->getFacade()->getCompanyUserById(
            $companyUserResponseTransfer->getCompanyUser()
                ->getIdCompanyUser()
        );

        // Assert
        $this->assertNotNull($foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testCreateInitialCompanyUserShouldPersistCompanyUser(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = (new CompanyUserBuilder())->withCustomer()->build();
        $companyTransfer->setInitialUserTransfer($companyUserTransfer);
        $companyResponseTransfer = (new CompanyResponseBuilder(['company_transfer' => $companyTransfer]))->build();

        // Act
        $companyResponseTransfer = $this->getFacade()->createInitialCompanyUser($companyResponseTransfer);
        $foundCompanyUserTransfer = $this->getFacade()->getCompanyUserById(
            $companyResponseTransfer->getCompanyTransfer()
                ->getInitialUserTransfer()
                ->getIdCompanyUser()
        );

        // Assert
        $this->assertNotNull($foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testUpdateShouldPersistCompanyUserChanges(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );

        $oldCustomerName = $customerTransfer->getFirstName();
        $companyUserTransfer->setCustomer($customerTransfer);
        $companyUserTransfer->getCustomer()->setFirstName('TESTER');
        // Act
        $companyUserResponseTransfer = $this->getFacade()->update($companyUserTransfer);

        // Assert
        $this->assertSame('TESTER', $companyUserResponseTransfer->getCompanyUser()->getCustomer()->getFirstName());
    }

    /**
     * @return void
     */
    public function testDeleteShouldRemoveCompanyUserFromStorage(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );

        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

        // Act
        $this->getFacade()->delete($companyUserTransfer);

        // Assert
        $this->expectException(TypeError::class);
        $this->getFacade()->getCompanyUserById($idCompanyUser);
    }

    /**
     * @return void
     */
    public function testFindCompanyUserByCustomerIdShouldReturnTransfer(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );

        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->getFacade()->findCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNotNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUserByCustomerIdShouldReturnTransfer(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany(['is_active' => true]);
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );

        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->getFacade()->findCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNotNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionShouldReturnTransfer(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany(['is_active' => true]);
        $customerTransfer = (new CustomerBuilder())->build();
        $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterBuilder(['id_company' => $companyTransfer->getIdCompany()]))->build();

        // Act
        $companyUserCollectionTransfer = $this->getFacade()->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyUserCollectionTransfer->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserByIdShouldReturnTransfer(): void
    {
        // Assign
        $companyTransfer = $this->tester->haveCompany(['is_active' => true]);
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->haveCompanyUser(
            [
                'customer' => $customerTransfer,
                'fk_company' => $companyTransfer->getIdCompany(),
            ]
        );

        // Act
        $foundCompanyUserTransfer = $this->getFacade()->getCompanyUserById($companyUserTransfer->getIdCompanyUser());

        // Assert
        $this->assertNotNull($foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testFindInitialCompanyUserByCompanyIdShouldReturnInitialUserTransfer(): void
    {
        // Assign
        $initialCompanyUserId = null;
        $companyTransfer = $this->tester->haveCompany(['is_active' => true]);

        for ($i = 0; $i < 5; $i++) {
            $customerTransfer = (new CustomerBuilder())->build();
            $companyUserTransfer = $this->tester->haveCompanyUser(
                [
                    'customer' => $customerTransfer,
                    'fk_company' => $companyTransfer->getIdCompany(),
                ]
            );

            if ($initialCompanyUserId === null) {
                $initialCompanyUserId = $companyUserTransfer->getIdCompanyUser();
            }
        }

        // Act
        $companyUserTransfer = $this->getFacade()->findInitialCompanyUserByCompanyId($companyTransfer->getIdCompany());

        // Assert
        $this->assertSame($initialCompanyUserId, $companyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testCountActiveCompanyUsersByIdCustomerCountsActiveCompanyUsersOnly(): void
    {
        //Arrange
        $expectedCompanyUserAmount = 1;
        $customer = $this->tester->haveCustomer();
        $activeCompany = $this->tester->haveCompany(['isActive' => true]);
        $inactiveCompany = $this->tester->haveCompany(['isActive' => false]);

        $seedDataWithActiveCompany = [
            CompanyUserTransfer::CUSTOMER => $customer,
            CompanyUserTransfer::FK_COMPANY => $activeCompany->getIdCompany(),
        ];
        $seedDataWithInactiveCompany = [
            CompanyUserTransfer::CUSTOMER => $customer,
            CompanyUserTransfer::FK_COMPANY => $inactiveCompany->getIdCompany(),
        ];
        $this->tester->haveCompanyUser($seedDataWithActiveCompany);
        $this->tester->haveCompanyUser($seedDataWithInactiveCompany);

        //Act
        $actualCompanyUserAmount = $this->tester->getFacade()->countActiveCompanyUsersByIdCustomer($customer);

        //Assert
        $this->tester->assertEquals($expectedCompanyUserAmount, $actualCompanyUserAmount);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
