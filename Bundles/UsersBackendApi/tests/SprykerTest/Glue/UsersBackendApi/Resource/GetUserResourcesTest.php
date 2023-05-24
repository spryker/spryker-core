<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\UsersBackendApi\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiUsersAttributesTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerTest\Glue\UsersBackendApi\UsersBackendApiResourceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group UsersBackendApi
 * @group Resource
 * @group GetUserResourcesTest
 * Add your own group annotations below this line
 */
class GetUserResourcesTest extends Unit
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
    public function testShouldReturnCollectionOfUserResourcesFilteredByUuid(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addUuid($userTransfer->getUuidOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUserResources($userCriteriaTransfer);

        // Assert
        $this->assertUserResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfUserResourcesFilteredByIdUser(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($userTransfer->getIdUserOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUserResources($userCriteriaTransfer);

        // Assert
        $this->assertUserResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionOfUserResourcesFilteredByUsername(): void
    {
        // Arrange
        $this->tester->haveUser();
        $userTransfer = $this->tester->haveUser();

        $userConditionsTransfer = (new UserConditionsTransfer())->addUsername($userTransfer->getUsernameOrFail());
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        // Act
        $userResourceCollectionTransfer = $this->tester->getResource()->getUserResources($userCriteriaTransfer);

        // Assert
        $this->assertUserResourceCollectionTransfer($userResourceCollectionTransfer, $userTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     * @param \Generated\Shared\Transfer\UserTransfer $expectedUserTransfer
     *
     * @return void
     */
    protected function assertUserResourceCollectionTransfer(
        UserResourceCollectionTransfer $userResourceCollectionTransfer,
        UserTransfer $expectedUserTransfer
    ): void {
        $this->assertCount(1, $userResourceCollectionTransfer->getUserResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $userResourceTransfer */
        $userResourceTransfer = $userResourceCollectionTransfer->getUserResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_TYPE_USERS, $userResourceTransfer->getType());
        $this->assertSame($expectedUserTransfer->getUuidOrFail(), $userResourceTransfer->getId());
        $this->assertInstanceOf(ApiUsersAttributesTransfer::class, $userResourceTransfer->getAttributes());

        /** @var \Generated\Shared\Transfer\ApiUsersAttributesTransfer $apiUsersAttributesTransfer */
        $apiUsersAttributesTransfer = $userResourceTransfer->getAttributesOrFail();
        $this->assertSame($expectedUserTransfer->getFirstNameOrFail(), $apiUsersAttributesTransfer->getFirstName());
        $this->assertSame($expectedUserTransfer->getLastNameOrFail(), $apiUsersAttributesTransfer->getLastName());
        $this->assertSame($expectedUserTransfer->getUsernameOrFail(), $apiUsersAttributesTransfer->getUsername());
    }
}
