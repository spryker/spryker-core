<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\HttpRequestTransfer;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;
use Orm\Zed\Auth\Persistence\SpyResetPasswordQuery;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Zed\Auth\Business\AuthBusinessFactory;
use Spryker\Zed\Auth\Business\AuthFacade;
use Spryker\Zed\Auth\Business\Model\Auth;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Business
 * @group Facade
 * @group AuthFacadeTest
 * Add your own group annotations below this line
 */
class AuthFacadeTest extends Unit
{
    public const TEST_MAIL = 'username@example.com';
    protected const TEST_SYSTEM_USER_TOKEN = 'token';

    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacade
     */
    protected $authFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authFacade = new AuthFacade();
    }

    /**
     * @return void
     */
    public function testResetRequestCheckIfStatusIsActiveAndTokenIsSet(): void
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $resetStatus = $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());

        $this->assertSame($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
        $this->assertNotEmpty($passwordEntity->getCode());
        $this->assertTrue($resetStatus);
    }

    /**
     * @return void
     */
    public function testRequestPasswordEmailNotExistingShouldReturnFalse(): void
    {
        $result = $this->authFacade->requestPasswordReset('username1@example.com');
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testPasswordResetWhenTokenIsValidStateShouldBeChangedToUsed(): void
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());

        $resetStatus = $this->authFacade->resetPassword($passwordEntity->getCode(), 'new');

        $passwordEntity->reload();

        $this->assertTrue($resetStatus);
        $this->assertSame($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_USED);
    }

    /**
     * @return void
     */
    public function testValidateTokenExpirityShouldStateSetToExpired(): void
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());
        $expiredDateTime = new DateTime('last year');
        $passwordEntity->setCreatedAt($expiredDateTime);
        $passwordEntity->save();

        $resetStatus = $this->authFacade->isValidPasswordResetToken($passwordEntity->getCode());

        $passwordEntity->reload();

        $this->assertSame($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_EXPIRED);
        $this->assertFalse($resetStatus);
    }

    /**
     * @return void
     */
    public function testValidatePasswordWhenTokenProvidedNotSavedToDatabase(): void
    {
        $resetStatus = $this->authFacade->isValidPasswordResetToken('NERAMANES');
        $this->assertFalse($resetStatus);
    }

    /**
     * @return void
     */
    public function testIsSystemUserRequestReturnsTrue(): void
    {
        // Arrange
        $httpRequestTransfer = (new HttpRequestTransfer())
            ->addHeader(strtolower(AuthConstants::AUTH_TOKEN), static::TEST_SYSTEM_USER_TOKEN);

        $this->authFacade->setFactory($this->createAuthBusinessFactoryMock());

        // Act
        $isSystemUserRequest = $this->authFacade->isSystemUserRequest($httpRequestTransfer);

        // Assert
        $this->assertTrue($isSystemUserRequest);
    }

    /**
     * @return void
     */
    public function testIsSystemUserRequestReturnsFalseWithIncorrectToken(): void
    {
        // Arrange
        $httpRequestTransfer = (new HttpRequestTransfer())
            ->addHeader(strtolower(AuthConstants::AUTH_TOKEN), static::TEST_SYSTEM_USER_TOKEN);

        // Act
        $isSystemUserRequest = $this->authFacade->isSystemUserRequest($httpRequestTransfer);

        // Assert
        $this->assertFalse($isSystemUserRequest);
    }

    /**
     * @return void
     */
    public function testIsSystemUserRequestReturnsFalseWithNoToken(): void
    {
        // Arrange
        $httpRequestTransfer = new HttpRequestTransfer();

        // Act
        $isSystemUserRequest = $this->authFacade->isSystemUserRequest($httpRequestTransfer);

        // Assert
        $this->assertFalse($isSystemUserRequest);
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUser
     */
    protected function createTestUser(): SpyUser
    {
        $userEntity = new SpyUser();
        $userEntity->setUsername(self::TEST_MAIL);
        $userEntity->setFirstName('FirstName');
        $userEntity->setLastName('LastName');
        $userEntity->setPassword('Secret');
        $userEntity->setStatus(0);

        return $userEntity;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Auth\Business\AuthBusinessFactory
     */
    protected function createAuthBusinessFactoryMock(): AuthBusinessFactory
    {
        $authModelMock = $this->getMockBuilder(Auth::class)
            ->onlyMethods(['hasSystemUserByHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $authModelMock->method('hasSystemUserByHash')
            ->willReturn(true);

        $authBusinessFactoryMock = $this->getMockBuilder(AuthBusinessFactory::class)
            ->onlyMethods(['createAuthModel'])
            ->getMock();
        $authBusinessFactoryMock->method('createAuthModel')
            ->willReturn($authModelMock);

        return $authBusinessFactoryMock;
    }
}
