<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Auth\Business;

use Codeception\TestCase\Test;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use Orm\Zed\Auth\Persistence\SpyResetPasswordQuery;
use Orm\Zed\User\Persistence\SpyUser;
use Orm\Zed\Auth\Persistence\Map\SpyResetPasswordTableMap;

class AuthFacadeTest extends Test
{
    const TEST_MAIL = 'username@example.com';

    /**
     * @var AuthFacade
     */
    protected $authFacade;

    public function setUp()
    {
        parent::setUp();

        $locator = Locator::getInstance();

        $this->authFacade = $locator->auth()->facade();
    }

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

    public function testRequestPasswordEmailNotExistingShouldThrowException()
    {
        $this->setExpectedException('SprykerFeature\Zed\User\Business\Exception\UserNotFoundException');
        $this->authFacade->requestPasswordReset('username1@example.com');
    }

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

    public function testValidateTokenExpirityShouldStateSetToExpired()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset(self::TEST_MAIL);

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUser($userEntity->getIdUser());
        $expiredDateTime = new \DateTime('last year');
        $passwordEntity->setCreatedAt($expiredDateTime);
        $passwordEntity->save();

        $resetStatus = $this->authFacade->isValidPasswordResetToken($passwordEntity->getCode());

        $passwordEntity->reload();

        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_EXPIRED);
        $this->assertFalse($resetStatus);
    }

    public function testValidatePasswordWhenTokenProvidedNotSavedToDatabase()
    {
        $resetStatus = $this->authFacade->isValidPasswordResetToken('NERAMANES');
        $this->assertFalse($resetStatus);
    }

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
