<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Provider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\Provider\AgentMerchantUserProvider;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser;
use Spryker\Zed\MerchantAgent\Communication\Plugin\User\MerchantAgentUserQueryCriteriaExpanderPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser;
use SprykerTest\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiCommunicationTester;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group Provider
 * @group AgentMerchantUserProviderTest
 * Add your own group annotations below this line
 */
class AgentMerchantUserProviderTest extends Unit
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     *
     * @var string
     */
    protected const STATUS_BLOCKED = 'blocked';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED
     *
     * @var string
     */
    protected const STATUS_DELETED = 'deleted';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const STATUS_ACTIVE = 'active';

    /**
     * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::ROLE_MERCHANT_AGENT
     *
     * @var string
     */
    protected const ROLE_MERCHANT_AGENT = 'ROLE_MERCHANT_AGENT';

    /**
     * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::ROLE_ALLOWED_TO_SWITCH
     *
     * @var string
     */
    protected const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig::ROLE_MERCHANT_USER
     *
     * @var string
     */
    protected const ROLE_MERCHANT_USER = 'ROLE_MERCHANT_USER';

    /**
     * @uses \Spryker\Zed\User\UserDependencyProvider::PLUGINS_USER_QUERY_CRITERIA_EXPANDER
     *
     * @var string
     */
    protected const PLUGINS_USER_QUERY_CRITERIA_EXPANDER = 'PLUGINS_USER_QUERY_CRITERIA_EXPANDER';

    /**
     * @var \SprykerTest\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiCommunicationTester
     */
    protected AgentSecurityMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_USER_QUERY_CRITERIA_EXPANDER, [
            new MerchantAgentUserQueryCriteriaExpanderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testRefreshUserReturnsAgentMerchantUserWhenUserIsActiveMerchantAgent(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::IS_MERCHANT_AGENT => true,
            UserTransfer::STATUS => static::STATUS_ACTIVE,
        ]);
        $user = new AgentMerchantUser($userTransfer);

        // Act
        $refreshedUser = (new AgentMerchantUserProvider())->refreshUser($user);

        // Assert
        $this->assertInstanceOf(AgentMerchantUser::class, $refreshedUser);
        $this->assertNotNull($refreshedUser->getUserIdentifier());
        $this->assertNotEmpty($refreshedUser->getRoles());
        $this->assertContains(static::ROLE_MERCHANT_AGENT, $refreshedUser->getRoles());
        $this->assertContains(static::ROLE_ALLOWED_TO_SWITCH, $refreshedUser->getRoles());
        $this->assertSame($userTransfer->getIdUserOrFail(), $refreshedUser->getUserTransfer()->getIdUser());
    }

    /**
     * @return void
     */
    public function testRefreshUserReturnsUnmodifiedMerchantUserWhenAgentIsImpersonatorOfMerchantUser(): void
    {
        // Arrange
        $agentUserTransfer = $this->tester->haveUser([
            UserTransfer::IS_MERCHANT_AGENT => true,
            UserTransfer::STATUS => static::STATUS_ACTIVE,
        ]);

        $merchantUserTransfer = (new MerchantUserTransfer())
            ->setUser($this->tester->haveUser())
            ->setAgentUsername($agentUserTransfer->getUsernameOrFail());
        $user = new MerchantUser($merchantUserTransfer);

        // Act
        $refreshedUser = (new AgentMerchantUserProvider())->refreshUser($user);

        // Assert
        $this->assertInstanceOf(MerchantUser::class, $refreshedUser);
        $this->assertSame($user, $refreshedUser);
    }

    /**
     * @return void
     */
    public function testRefreshUserReturnsUnmodifiedMerchantUserWhenAgentUsernameIsNotProvidedInMerchantUserTransfer(): void
    {
        // Arrange
        $merchantUserTransfer = (new MerchantUserTransfer())
            ->setUser($this->tester->haveUser());
        $user = new MerchantUser($merchantUserTransfer);

        // Act
        $refreshedUser = (new AgentMerchantUserProvider())->refreshUser($user);

        // Assert
        $this->assertInstanceOf(MerchantUser::class, $refreshedUser);
        $this->assertSame($user, $refreshedUser);
    }

    /**
     * @dataProvider refreshUserThrowsUserNotFoundExceptionDataProvider
     *
     * @param array<string, string|bool> $userData
     *
     * @return void
     */
    protected function testRefreshUserThrowsUserNotFoundExceptionForAgentMerchantUser(array $userData): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser($userData);
        $user = new AgentMerchantUser($userTransfer);

        // Assert
        $this->expectException(UserNotFoundException::class);

        // Act
        (new AgentMerchantUserProvider())->refreshUser($user);
    }

    /**
     * @dataProvider refreshUserThrowsUserNotFoundExceptionDataProvider
     *
     * @param array<string, string|bool> $userData
     *
     * @return void
     */
    public function testRefreshUserThrowsUserNotFoundExceptionForMerchantUser(array $userData): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser($userData);

        $merchantUserTransfer = (new MerchantUserTransfer())
            ->setUser($this->tester->haveUser())
            ->setAgentUsername($userTransfer->getUsernameOrFail());
        $user = new MerchantUser($merchantUserTransfer);

        // Assert
        $this->expectException(UserNotFoundException::class);

        // Act
        (new AgentMerchantUserProvider())->refreshUser($user);
    }

    /**
     * @return array<string, list<array<string, string|bool>>>
     */
    protected function refreshUserThrowsUserNotFoundExceptionDataProvider(): array
    {
        return [
            'user is deactivated' => [
                [
                    UserTransfer::IS_MERCHANT_AGENT => true,
                    UserTransfer::STATUS => static::STATUS_BLOCKED,
                ],
            ],
            'user is deleted' => [
                [
                    UserTransfer::IS_MERCHANT_AGENT => true,
                    UserTransfer::STATUS => static::STATUS_DELETED,
                ],
            ],
            'user is not an agent' => [
                [
                    UserTransfer::IS_MERCHANT_AGENT => false,
                ],
            ],
        ];
    }
}
