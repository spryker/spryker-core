<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\CompanyResponseBuilder;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\CompanyUserCriteriaFilterBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

/**
 * Auto-generated group annotations
 *
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
    protected const FIRST_NAME_TEST = 'TEST_NAME';

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
        $companyUserTransfer = (new CompanyUserBuilder([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]))->withCustomer()->build();

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->create($companyUserTransfer);
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserById(
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
        $companyResponseTransfer = (new CompanyResponseBuilder([CompanyResponseTransfer::COMPANY_TRANSFER => $companyTransfer]))->build();

        // Act
        $companyResponseTransfer = $this->tester->getFacade()
            ->createInitialCompanyUser($companyResponseTransfer);
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserById(
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
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $companyUserTransfer->getCustomer()
            ->setFirstName(static::FIRST_NAME_TEST);

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->update($companyUserTransfer);

        // Assert
        $this->assertSame(static::FIRST_NAME_TEST, $companyUserResponseTransfer->getCompanyUser()->getCustomer()->getFirstName());
    }

    /**
     * @return void
     */
    public function testDeleteShouldRemoveCompanyUserFromStorage(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

        // Act
        $this->tester->getFacade()
            ->delete($companyUserTransfer);

        // Assert
        $this->assertNull(
            $this->tester->getFacade()
                ->findCompanyUserById($idCompanyUser)
        );
    }

    /**
     * @return void
     */
    public function testFindCompanyUserByCustomerIdShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNotNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testFindCompanyUserByCustomerIdShouldReturnNullWhenCustomerDoesNotExists(): void
    {
        // Assign
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer(0);

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUserByCustomerIdShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => true],
            [CompanyTransfer::IS_ACTIVE => true]
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findActiveCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNotNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUserByCustomerIdShouldReturnNullWhenCompanyIsNotActive(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => true],
            [CompanyTransfer::IS_ACTIVE => false]
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findActiveCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUserByCustomerIdShouldReturnNullWhenCompanyUserIsNotActive(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false],
            [CompanyTransfer::IS_ACTIVE => true]
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findActiveCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterBuilder([
            CompanyUserCriteriaFilterTransfer::ID_COMPANY => $companyUserTransfer->getFkCompany(),
        ]))->build();

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $companyUserCollectionTransfer->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionIgnoresAnonymizedCustomers(): void
    {
        // Assign
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setAnonymizedAt(new DateTime());
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer]
        );
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterBuilder([
            CompanyUserCriteriaFilterTransfer::ID_COMPANY => $companyUserTransfer->getFkCompany(),
        ]))->build();

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        // Assert
        $this->assertCount(0, $companyUserCollectionTransfer->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testGetActiveCompanyUsersByCustomerReferenceShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()
            ->getActiveCompanyUsersByCustomerReference($companyUserTransfer->getCustomer());

        // Assert
        $this->assertCount(1, $companyUserCollectionTransfer->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testGetActiveCompanyUsersByCustomerReferenceShouldNotReturnInactiveCompanyUsers(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false],
            [CompanyTransfer::IS_ACTIVE => true]
        );
        $customerTransfer = $companyUserTransfer->getCustomer();

        // Act
        $companyUserCollectionTransfer = $this->tester->getFacade()
            ->getActiveCompanyUsersByCustomerReference($customerTransfer);

        // Assert
        $this->assertCount(0, $companyUserCollectionTransfer->getCompanyUsers());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserByIdShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserById($companyUserTransfer->getIdCompanyUser());

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
        $companyTransfer = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);

        for ($i = 0; $i < 5; $i++) {
            $companyUserTransfer = $this->tester->createCompanyUserTransfer(
                [CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]
            );

            if ($initialCompanyUserId === null) {
                $initialCompanyUserId = $companyUserTransfer->getIdCompanyUser();
            }
        }

        // Act
        $companyUserTransfer = $this->tester->getFacade()
            ->findInitialCompanyUserByCompanyId($companyTransfer->getIdCompany());

        // Assert
        $this->assertSame($initialCompanyUserId, $companyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testCountActiveCompanyUsersByIdCustomerCountsActiveCompanyUsersOnly(): void
    {
        //Arrange
        $expectedCount = 1;
        $customerTransfer = $this->tester->haveCustomer();
        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
            [CompanyTransfer::IS_ACTIVE => true]
        );
        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
            [CompanyTransfer::IS_ACTIVE => false]
        );

        //Act
        $actualCompanyUserAmount = $this->tester->getFacade()
            ->countActiveCompanyUsersByIdCustomer($customerTransfer);

        //Assert
        $this->tester->assertEquals($expectedCount, $actualCompanyUserAmount);
    }

    /**
     * @return void
     */
    public function testEnableCompanyUserShouldEnableInactiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false]
        );

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->enableCompanyUser($companyUserTransfer);

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testEnableCompanyUserShouldNotEnableActiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->enableCompanyUser($companyUserTransfer);

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDisableCompanyUserShouldDisableActiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->disableCompanyUser($companyUserTransfer);

        // Assert
        $this->assertTrue($companyUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testDisableCompanyUserShouldNotDisableInactiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false]
        );

        // Act
        $companyUserResponseTransfer = $this->tester->getFacade()
            ->disableCompanyUser($companyUserTransfer);

        // Assert
        $this->assertFalse($companyUserResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUsersWillReturnArrayOfActiveCompanyUsers(): void
    {
        //Assign
        $activeCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => true]
        );
        $inActiveCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false]
        );
        $companyUserIds = [
            $activeCompanyUserTransfer->getIdCompanyUser(),
            $inActiveCompanyUserTransfer->getIdCompanyUser(),
        ];

        //Act
        $activeCompanyUsers = $this->tester->getFacade()
            ->findActiveCompanyUsersByIds($companyUserIds);

        //Assert
        $this->assertCount(1, $activeCompanyUsers);
        $this->assertSame($activeCompanyUsers[0]->getIdCompanyUser(), $activeCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testFindActiveCompanyUserIdsByCompanyIdsShouldReturnIdsOfActiveCompanyUsers(): void
    {
        //Assign
        $activeCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => true]
        );
        $inActiveCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false]
        );
        $companyIds = [
            $activeCompanyUserTransfer->getFkCompany(),
            $inActiveCompanyUserTransfer->getFkCompany(),
        ];

        //Act
        $activeCompanyUsers = $this->tester->getFacade()
            ->findActiveCompanyUserIdsByCompanyIds($companyIds);

        //Assert
        $this->assertCount(1, $activeCompanyUsers);
        $this->assertEquals($activeCompanyUsers[0], $activeCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyUserShouldRemoveCompanyUserFromStorageWithoutCustomerAnonymizing(): void
    {
        // Assign
        $customerTransfer = (new CustomerBuilder())->build();
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer]
        );
        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

        // Act
        $this->tester->getFacade()
            ->deleteCompanyUser($companyUserTransfer);
        $companyUserTransferFetched = $this->tester->getFacade()
            ->findCompanyUserById($idCompanyUser);

        // Assert
        $this->assertNull($companyUserTransferFetched);
        $this->assertSame($customerTransfer, $this->tester->getCustomerFacade()->getCustomer($customerTransfer));
    }

    /**
     * @return void
     */
    public function testFindCompanyUserByIdShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->findCompanyUserById($companyUserTransfer->getIdCompanyUser());

        // Assert
        $this->assertNotNull($foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionByCriteriaRetrievesCompanyUsersByEmailPattern(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        $companyUserCriteriaTransfer = (new CompanyUserCriteriaTransfer())
            ->setPattern($companyUserTransfer->getCustomer()->getEmail());

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers()
            ->offsetGet(0);

        // Assert
        $this->assertEquals($companyUserTransfer->getIdCompanyUser(), $foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionByCriteriaRetrievesCompanyUsersByFirstNamePattern(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        $companyUserCriteriaTransfer = (new CompanyUserCriteriaTransfer())
            ->setPattern($companyUserTransfer->getCustomer()->getFirstName());

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers()
            ->offsetGet(0);

        // Assert
        $this->assertEquals($companyUserTransfer->getIdCompanyUser(), $foundCompanyUserTransfer->getIdCompanyUser());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserCollectionByCriteriaRetrievesCompanyUsersByLastNamePattern(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        $companyUserCriteriaTransfer = (new CompanyUserCriteriaTransfer())
            ->setPattern($companyUserTransfer->getCustomer()->getLastName());

        // Act
        $foundCompanyUserTransfer = $this->tester->getFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers()
            ->offsetGet(0);

        // Assert
        $this->assertEquals($companyUserTransfer->getIdCompanyUser(), $foundCompanyUserTransfer->getIdCompanyUser());
    }
}
