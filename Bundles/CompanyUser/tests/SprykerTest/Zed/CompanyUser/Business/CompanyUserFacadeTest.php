<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUser\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyResponseBuilder;
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

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
class CompanyUserFacadeTest extends Unit
{
    use BusinessHelperTrait;

    /**
     * @var string
     */
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
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyUserTransfer = (new CompanyUserBuilder([
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]))->withCustomer()->build();
        $companyUserFacade = $this->tester->createCompanyUserFacade();

        // Act
        $companyUserResponseTransfer = $companyUserFacade
            ->create($companyUserTransfer);
        $foundCompanyUserTransfer = $companyUserFacade
            ->getCompanyUserById(
                $companyUserResponseTransfer->getCompanyUser()
                    ->getIdCompanyUser(),
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
        $companyUserFacade = $this->tester->createCompanyUserFacade();

        // Act
        $companyResponseTransfer = $companyUserFacade
            ->createInitialCompanyUser($companyResponseTransfer);
        $foundCompanyUserTransfer = $companyUserFacade
            ->getCompanyUserById(
                $companyResponseTransfer->getCompanyTransfer()
                    ->getInitialUserTransfer()
                    ->getIdCompanyUser(),
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
        $companyUserFacade = $this->tester->createCompanyUserFacade();

        // Act
        $companyUserResponseTransfer = $companyUserFacade
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
        $companyUserFacade = $this->tester->createCompanyUserFacade();

        // Act
        $companyUserFacade
            ->delete($companyUserTransfer);

        // Assert
        $this->assertNull(
            $companyUserFacade
                ->findCompanyUserById($idCompanyUser),
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
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
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
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyTransfer::IS_ACTIVE => true],
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyTransfer::IS_ACTIVE => false],
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyTransfer::IS_ACTIVE => true],
        );
        $customerTransfer = (new CustomerBuilder())->build();
        $customerTransfer->setIdCustomer($companyUserTransfer->getFkCustomer());

        // Act
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
            ->findActiveCompanyUserByCustomerId($customerTransfer);

        // Assert
        $this->assertNull($companyUserTransfer);
    }

    /**
     * @return void
     */
    public function testGetActiveCompanyUsersByCustomerReferenceShouldReturnTransfer(): void
    {
        // Assign
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();

        // Act
        $companyUserCollectionTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyTransfer::IS_ACTIVE => true],
        );
        $customerTransfer = $companyUserTransfer->getCustomer();

        // Act
        $companyUserCollectionTransfer = $this->tester->createCompanyUserFacade()
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
        $foundCompanyUserTransfer = $this->tester->createCompanyUserFacade()
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
                [CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany()],
            );

            if ($initialCompanyUserId === null) {
                $initialCompanyUserId = $companyUserTransfer->getIdCompanyUser();
            }
        }

        // Act
        $companyUserTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyTransfer::IS_ACTIVE => true],
        );
        $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
            [CompanyTransfer::IS_ACTIVE => false],
        );
        $this->tester->createCompanyUserTransfer(
            [
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
                CompanyUserTransfer::IS_ACTIVE => false,
            ],
            [CompanyTransfer::IS_ACTIVE => true],
        );

        //Act
        $actualCompanyUserAmount = $this->tester->createCompanyUserFacade()
            ->countActiveCompanyUsersByIdCustomer($customerTransfer);

        //Assert
        $this->tester->assertSame($expectedCount, $actualCompanyUserAmount);
    }

    /**
     * @return void
     */
    public function testEnableCompanyUserShouldEnableInactiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false],
        );

        // Act
        $companyUserResponseTransfer = $this->tester->createCompanyUserFacade()
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
        $companyUserResponseTransfer = $this->tester->createCompanyUserFacade()
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
        $companyUserResponseTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyUserTransfer::IS_ACTIVE => false],
        );

        // Act
        $companyUserResponseTransfer = $this->tester->createCompanyUserFacade()
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
            [CompanyUserTransfer::IS_ACTIVE => true],
        );
        $inActiveCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false],
        );
        $companyUserIds = [
            $activeCompanyUserTransfer->getIdCompanyUser(),
            $inActiveCompanyUserTransfer->getIdCompanyUser(),
        ];

        //Act
        $activeCompanyUsers = $this->tester->createCompanyUserFacade()
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
            [CompanyUserTransfer::IS_ACTIVE => true],
        );
        $inActiveCompanyUserTransfer = $this->tester->createCompanyUserTransfer(
            [CompanyUserTransfer::IS_ACTIVE => false],
        );
        $companyIds = [
            $activeCompanyUserTransfer->getFkCompany(),
            $inActiveCompanyUserTransfer->getFkCompany(),
        ];

        //Act
        $activeCompanyUsers = $this->tester->createCompanyUserFacade()
            ->findActiveCompanyUserIdsByCompanyIds($companyIds);

        //Assert
        $this->assertCount(1, $activeCompanyUsers);
        // TODO: use assertSame() once the actual return result is of int, and not string
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
            [CompanyUserTransfer::CUSTOMER => $customerTransfer],
        );
        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();
        $companyUserFacade = $this->tester->createCompanyUserFacade();

        // Act
        $companyUserFacade
            ->deleteCompanyUser($companyUserTransfer);
        $companyUserTransferFetched = $companyUserFacade
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
        $foundCompanyUserTransfer = $this->tester->createCompanyUserFacade()
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
        $foundCompanyUserTransfer = $this->tester->createCompanyUserFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers()
            ->offsetGet(0);

        // Assert
        $this->assertSame($companyUserTransfer->getIdCompanyUser(), $foundCompanyUserTransfer->getIdCompanyUser());
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
        $companyUserCollectionTransfer = $this->tester->createCompanyUserFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers();

        // Assert
        $companyUserIds = array_map(function (CompanyUserTransfer $collectionItem) {
            return $collectionItem->getIdCompanyUser();
        }, $companyUserCollectionTransfer->getArrayCopy());
        $this->assertContains($companyUserTransfer->getIdCompanyUser(), $companyUserIds);
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
        $companyUserCollectionTransfer = $this->tester->createCompanyUserFacade()
            ->getCompanyUserCollectionByCriteria($companyUserCriteriaTransfer)
            ->getCompanyUsers();

        // Assert
        $companyUserIds = array_map(function (CompanyUserTransfer $collectionItem) {
            return $collectionItem->getIdCompanyUser();
        }, $companyUserCollectionTransfer->getArrayCopy());
        $this->assertContains($companyUserTransfer->getIdCompanyUser(), $companyUserIds);
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsSuccess(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer();
        $customerTransfer = (new CustomerTransfer())->setIdCustomer($companyUserTransfer->getFkCustomerOrFail());

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertTrue($result->getIsActiveCompanyUserExists());
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsWithNotActiveUser(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [
                CompanyUserTransfer::IS_ACTIVE => false,
            ],
        );
        $customerTransfer = (new CustomerTransfer())->setIdCustomer($companyUserTransfer->getFkCustomerOrFail());

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertFalse($result->getIsActiveCompanyUserExists());
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsWithNotActiveCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [
                CompanyUserTransfer::IS_ACTIVE => true,
            ],
            [
                CompanyTransfer::IS_ACTIVE => false,
                CompanyTransfer::STATUS => 'approved',
            ],
        );
        $customerTransfer = (new CustomerTransfer())->setIdCustomer($companyUserTransfer->getFkCustomerOrFail());

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertFalse($result->getIsActiveCompanyUserExists());
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsWithPendingCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [
                CompanyUserTransfer::IS_ACTIVE => true,
            ],
            [
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::STATUS => 'pending',
            ],
        );
        $customerTransfer = (new CustomerTransfer())->setIdCustomer($companyUserTransfer->getFkCustomerOrFail());

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertFalse($result->getIsActiveCompanyUserExists());
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsWithDeniedCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserTransfer(
            [
                CompanyUserTransfer::IS_ACTIVE => true,
            ],
            [
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::STATUS => 'pending',
            ],
        );
        $customerTransfer = (new CustomerTransfer())->setIdCustomer($companyUserTransfer->getFkCustomerOrFail());

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertFalse($result->getIsActiveCompanyUserExists());
    }

    /**
     * @return void
     */
    public function testExpandCustomerWithIsActiveCompanyUserExistsWithoutCompanyUsers(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $result = $this->tester->createCompanyUserFacade()
            ->expandCustomerWithIsActiveCompanyUserExists($customerTransfer);

        // Assert
        $this->assertNull($result->getIsActiveCompanyUserExists());
    }
}
