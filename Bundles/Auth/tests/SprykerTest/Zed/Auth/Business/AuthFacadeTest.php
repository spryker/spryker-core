<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Auth\Business;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;
use Orm\Zed\Auth\Persistence\SpyResetPasswordQuery;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Zed\Auth\Business\AuthFacade;

/**
 * Auto-generated group annotations
 * @group Functional
 * @group Spryker
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

    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacade
     */
    protected $authFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->authFacade = new AuthFacade();
    }

    /**
     * @return void
     */
    public function testResetRequestCheckIfStatusIsActiveAndTokenIsSet()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $resetStatus = $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());

        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
        $this->assertNotEmpty($passwordEntity->getCode());
        $this->assertTrue($resetStatus);
    }

    /**
     * @return void
     */
    public function testRequestPasswordEmailNotExistingShouldReturnFalse()
    {
        $result = $this->authFacade->requestPasswordReset('username1@example.com');
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testPasswordResetWhenTokenIsValidStateShouldBeChangedToUsed()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());

        $resetStatus = $this->authFacade->resetPassword($passwordEntity->getCode(), 'new');

        $passwordEntity->reload();

        $this->assertTrue($resetStatus);
        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_USED);
    }

    /**
     * @return void
     */
    public function testValidateTokenExpirityShouldStateSetToExpired()
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

        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_EXPIRED);
        $this->assertFalse($resetStatus);
    }

    /**
     * @return void
     */
    public function testValidatePasswordWhenTokenProvidedNotSavedToDatabase()
    {
        $resetStatus = $this->authFacade->isValidPasswordResetToken('NERAMANES');
        $this->assertFalse($resetStatus);
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUser
     */
    protected function createTestUser()
    {
        $userEntity = new SpyUser();
        $userEntity->setUsername(self::TEST_MAIL);
        $userEntity->setFirstName('FirstName');
        $userEntity->setLastName('LastName');
        $userEntity->setPassword('Secret');
        $userEntity->setStatus(0);

        return $userEntity;
    }
}
