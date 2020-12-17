<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthUser\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthUser
 * @group Business
 * @group Facade
 * @group ResolveOauthUserTest
 * Add your own group annotations below this line
 */
class ResolveOauthUserTest extends Unit
{
    protected const FAKE_EMAIL = 'fake@mail.com';
    protected const SOME_GROUP = 'SOME_GROUP';

    /**
     * @var \SprykerTest\Zed\SecurityOauthUser\SecurityOauthUserBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testResolveOauthUserShouldResolveOauthUserWhenSelectedStrategyIsAcceptExistingUsers(): void
    {
        // Arrange
        $securityOauthUserFacade = $this->tester->mockSecurityOauthUserFacade(
            SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS
        );

        $userTransfer = $this->tester->haveUser();

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail($userTransfer->getUsername());

        // Act
        $resolvedUserTransfer = $securityOauthUserFacade->resolveOauthUser($userCriteriaTransfer);

        //Assert
        $this->assertNotNull($resolvedUserTransfer, 'Expected that oauth user is resolved.');
    }

    /**
     * @return void
     */
    public function testResolveOauthUserShouldNotResolveOauthUserWithFakeEmailWhenSelectedStrategyIsAcceptExistingUsers(): void
    {
        // Arrange
        $securityOauthUserFacade = $this->tester->mockSecurityOauthUserFacade(
            SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS
        );

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail(static::FAKE_EMAIL);

        // Act
        $resolvedUserTransfer = $securityOauthUserFacade->resolveOauthUser($userCriteriaTransfer);

        //Assert
        $this->assertNull($resolvedUserTransfer, 'Expected that oauth user is not resolved.');
    }

    /**
     * @return void
     */
    public function testResolveOauthUserShouldCreateOauthUserWhenSelectedStrategyIsCreateUserOnFirstLogin(): void
    {
        // Arrange
        $securityOauthUserFacade = $this->tester->mockSecurityOauthUserFacade(
            SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN,
            static::SOME_GROUP
        );

        $this->tester->haveGroup([GroupTransfer::NAME => static::SOME_GROUP]);

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail(static::FAKE_EMAIL);

        // Act
        $resolvedUserTransfer = $securityOauthUserFacade->resolveOauthUser($userCriteriaTransfer);

        //Assert
        $this->assertNotNull($resolvedUserTransfer, 'Expected that oauth user is created.');
        $this->assertSame($resolvedUserTransfer->getUsername(), static::FAKE_EMAIL, 'Expected the same email.');
    }

    /**
     * @return void
     */
    public function testResolveOauthUserShouldResolveExistingOauthUserWhenSelectedStrategyIsCreateUserOnFirstLogin(): void
    {
        // Arrange
        $securityOauthUserFacade = $this->tester->mockSecurityOauthUserFacade(
            SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN,
        );

        $userTransfer = $this->tester->haveUser();

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail($userTransfer->getUsername());

        // Act
        $resolvedUserTransfer = $securityOauthUserFacade->resolveOauthUser($userCriteriaTransfer);

        // Assert
        $this->assertNotNull($resolvedUserTransfer, 'Expected that oauth user is resolved.');
        $this->assertSame($resolvedUserTransfer->getUsername(), $userTransfer->getUsername(), 'Expected the same email.');
    }

    /**
     * @dataProvider resolveOauthUserShouldNotResolveOauthUserWithInactiveStatusDataProvider
     *
     * @param string $authenticationStrategy
     *
     * @return void
     */
    public function testResolveOauthUserShouldNotResolveOauthUserWithInactiveStatus(string $authenticationStrategy): void
    {
        // Arrange
        $securityOauthUserFacade = $this->tester->mockSecurityOauthUserFacade($authenticationStrategy);

        $userTransfer = $this->tester->haveUser();

        $this->tester->getUserFacade()->deactivateUser(
            $userTransfer->getIdUser()
        );

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setEmail($userTransfer->getUsername());

        // Act
        $resolvedUserTransfer = $securityOauthUserFacade->resolveOauthUser($userCriteriaTransfer);

        //Assert
        $this->assertNull($resolvedUserTransfer, 'Expected that oauth user is not resolved.');
    }

    /**
     * @return string[][]
     */
    public function resolveOauthUserShouldNotResolveOauthUserWithInactiveStatusDataProvider(): array
    {
        return [
            [SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_ACCEPT_ONLY_EXISTING_USERS],
            [SecurityOauthUserConfig::AUTHENTICATION_STRATEGY_CREATE_USER_ON_FIRST_LOGIN],
        ];
    }

    /**
     * @return void
     */
    public function testResolveOauthUserShouldThrowAnExceptionWhenRequiredDataIsNotProvided(): void
    {
        // Arrange
        $userCriteriaTransfer = new UserCriteriaTransfer();

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getSecurityOauthUserFacade()->resolveOauthUser($userCriteriaTransfer);
    }
}
