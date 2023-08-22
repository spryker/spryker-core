<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\UsersBackendApi\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Generated\Shared\Transfer\UsersBackendApiAttributesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Glue\UsersBackendApi\UsersBackendApiResourceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group UsersBackendApi
 * @group Resource
 * @group GetUsersResourcesTest
 * Add your own group annotations below this line
 */
class GetUsersResourcesTest extends Unit
{
    /**
     * @uses \Spryker\Glue\UsersBackendApi\UsersBackendApiConfig::RESOURCE_TYPE_USERS
     *
     * @var string
     */
    protected const RESOURCE_TYPE_USERS = 'users';

    /**
     * @var \SprykerTest\Glue\UsersBackendApi\UsersBackendApiResourceTester
     */
    protected UsersBackendApiResourceTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfUsersResourcesFilteredByUuid(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userTransfer->getUuidOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUsersResources($userCriteriaTransfer);

        // Assert
        $this->assertUsersResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfUsersResourcesFilteredByIdUser(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($userTransfer->getIdUserOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUsersResources($userCriteriaTransfer);

        // Assert
        $this->assertUsersResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfUsersResourcesFilteredByUsername(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($userTransfer->getUsernameOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUsersResources($userCriteriaTransfer);

        // Assert
        $this->assertUsersResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $expectedUserTransfer
     *
     * @return void
     */
    protected function assertUsersResourceCollectionTransfer(
        UserResourceCollectionTransfer $userResourceCollectionTransfer,
        UserTransfer $expectedUserTransfer
    ): void {
        $this->assertCount(1, $userResourceCollectionTransfer->getUserResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $userResourceTransfer */
        $userResourceTransfer = $userResourceCollectionTransfer->getUserResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_TYPE_USERS, $userResourceTransfer->getType());
        $this->assertSame($expectedUserTransfer->getUuidOrFail(), $userResourceTransfer->getId());
        $this->assertInstanceOf(UsersBackendApiAttributesTransfer::class, $userResourceTransfer->getAttributes());

        /** @var \Generated\Shared\Transfer\UsersBackendApiAttributesTransfer $usersBackendApiAttributesTransfer */
        $usersBackendApiAttributesTransfer = $userResourceTransfer->getAttributesOrFail();
        $this->assertSame($expectedUserTransfer->getFirstNameOrFail(), $usersBackendApiAttributesTransfer->getFirstName());
        $this->assertSame($expectedUserTransfer->getLastNameOrFail(), $usersBackendApiAttributesTransfer->getLastName());
        $this->assertSame($expectedUserTransfer->getUsernameOrFail(), $usersBackendApiAttributesTransfer->getUsername());
    }
}
