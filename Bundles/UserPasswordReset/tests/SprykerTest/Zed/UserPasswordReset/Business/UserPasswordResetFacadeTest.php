<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UserPasswordReset\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\UserPasswordResetRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\UserPasswordReset\Persistence\Map\SpyResetPasswordTableMap;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group UserPasswordReset
 * @group Business
 * @group Facade
 * @group UserPasswordResetFacadeTest
 * Add your own group annotations below this line
 */
class UserPasswordResetFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_MAIL = 'username@example.com';

    /**
     * @var string
     */
    protected const TEST_SYSTEM_USER_TOKEN = 'token';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_DELETED
     *
     * @var string
     */
    protected const USER_STATUS_DELETED = 'deleted';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const USER_STATUS_ACTIVE = 'active';

    /**
     * @var \SprykerTest\Zed\UserPasswordReset\UserPasswordResetBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacade
     */
    protected $authFacade;

    /**
     * @return void
     */
    public function testRequestPasswordResetCreatesResetPassword(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::TEST_MAIL,
            UserTransfer::STATUS => static::USER_STATUS_ACTIVE,
        ]);

        // Act
        $result = $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($userTransfer->getUsername()),
        );

        // Assert
        $this->assertTrue($result);
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());
        $this->assertSame(SpyResetPasswordTableMap::COL_STATUS_ACTIVE, $resetPasswordTransfer->getStatus());
        $this->assertNotNull($resetPasswordTransfer->getCode());
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetReturnsFalseForNotExistingUser(): void
    {
        // Arrange
        $fakeUsername = 'user_not_exists@example.com';

        // Act
        $result = $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($fakeUsername),
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testResetPasswordResetsPasswordAndResetPasswordCode(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::TEST_MAIL,
        ]);
        $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($userTransfer->getUsername()),
        );
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());

        // Act
        $result = $this->tester->getUserPasswordReset()->setNewPassword($resetPasswordTransfer->getCode(), 'new_password');

        // Assert
        $this->assertTrue($result);
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());
        $this->assertSame(SpyResetPasswordTableMap::COL_STATUS_USED, $resetPasswordTransfer->getStatus());
    }

    /**
     * @return void
     */
    public function testIsValidPasswordResetTokenReturnsTrueFotValidToken(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::TEST_MAIL,
        ]);
        $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($userTransfer->getUsername()),
        );
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());

        // Act
        $isValidPasswordResetToken = $this->tester->getUserPasswordReset()->isValidPasswordResetToken($resetPasswordTransfer->getCode());

        // Assert
        $this->assertTrue($isValidPasswordResetToken);
    }

    /**
     * @return void
     */
    public function testIsValidPasswordResetTokenReturnsFalseFotInvalidToken(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::TEST_MAIL,
        ]);
        $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($userTransfer->getUsername()),
        );
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());
        $resetPasswordTransfer->setCreatedAt(
            (new DateTime('last year'))->format('Y-m-d H:i:s'),
        );
        $this->tester->updateResetPasswordByIdAuthResetPassword(
            $resetPasswordTransfer->getIdResetPassword(),
            $resetPasswordTransfer,
        );

        // Act
        $isValidExpiredPasswordResetToken = $this->tester->getUserPasswordReset()->isValidPasswordResetToken($resetPasswordTransfer->getCode());
        $isValidNotExistingPasswordResetToken = $this->tester->getUserPasswordReset()->isValidPasswordResetToken('NOT_EXISTING_TOKEN');

        // Assert
        $this->assertFalse($isValidExpiredPasswordResetToken);
        $this->assertFalse($isValidNotExistingPasswordResetToken);
    }

    /**
     * @return void
     */
    public function testRequestPasswordResetReturnsFalseForNonActiveUser(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::USERNAME => static::TEST_MAIL,
            UserTransfer::STATUS => static::USER_STATUS_DELETED,
        ]);

        // Act
        $isPasswordResetSuccessfully = $this->tester->getUserPasswordReset()->requestPasswordReset(
            (new UserPasswordResetRequestTransfer())
                ->setEmail($userTransfer->getUsername()),
        );

        // Assert
        $this->assertFalse($isPasswordResetSuccessfully, 'It is not possible to request restore password for non active user');
    }
}
