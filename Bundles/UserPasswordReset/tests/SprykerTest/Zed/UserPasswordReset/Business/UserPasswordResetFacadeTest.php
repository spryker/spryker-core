<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UserPasswordReset\Business;

use Codeception\Test\Unit;
use DateTime;
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
    public const TEST_MAIL = 'username@example.com';
    protected const TEST_SYSTEM_USER_TOKEN = 'token';

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
        ]);

        // Act
        $result = $this->tester->getUserPasswordReset()->requestPasswordReset($userTransfer->getUsername());

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
        $result = $this->tester->getUserPasswordReset()->requestPasswordReset($fakeUsername);

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
        $this->tester->getUserPasswordReset()->requestPasswordReset($userTransfer->getUsername());
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
        $this->tester->getUserPasswordReset()->requestPasswordReset($userTransfer->getUsername());
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
        $this->tester->getUserPasswordReset()->requestPasswordReset($userTransfer->getUsername());
        $resetPasswordTransfer = $this->tester->findResetPasswordTransferByIdUser($userTransfer->getIdUser());
        $resetPasswordTransfer->setCreatedAt(
            (new DateTime('last year'))->format('Y-m-d H:i:s')
        );
        $this->tester->updateResetPasswordByIdAuthResetPassword(
            $resetPasswordTransfer->getIdResetPassword(),
            $resetPasswordTransfer
        );

        // Act
        $isValidExpiredPasswordResetToken = $this->tester->getUserPasswordReset()->isValidPasswordResetToken($resetPasswordTransfer->getCode());
        $isValidNotExistingPasswordResetToken = $this->tester->getUserPasswordReset()->isValidPasswordResetToken('NOT_EXISTING_TOKEN');

        // Assert
        $this->assertFalse($isValidExpiredPasswordResetToken);
        $this->assertFalse($isValidNotExistingPasswordResetToken);
    }
}
