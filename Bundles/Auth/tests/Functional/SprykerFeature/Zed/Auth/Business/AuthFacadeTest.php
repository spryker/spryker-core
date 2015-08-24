<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Auth\Business;

use Codeception\TestCase\Test;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\Auth\Persistence\Propel\SpyResetPasswordQuery;
use SprykerFeature\Zed\User\Persistence\Propel\SpyUser;
use SprykerFeature\Zed\Auth\Persistence\Propel\Map\SpyResetPasswordTableMap;

class AuthFacadeTest extends Test
{

    /**
     * @var AuthFacade
     */
    protected $authFacade;

    public function setUp()
    {
        parent::setUp();

        Propel::disableInstancePooling();
        $locator = Locator::getInstance();

        $this->authFacade = $locator->auth()->facade();
    }

    public function testRequestPasswordReset()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $resetStatus = $this->authFacade->requestPasswordReset('username@domain.tld');

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUserId($userEntity->getIdUser());

        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_ACTIVE);
        $this->assertNotEmpty($passwordEntity->getCode());
        $this->assertTrue($resetStatus);

    }

    public function testRequestPasswordEmailNotExisting()
    {
        $this->setExpectedException('SprykerFeature\Zed\User\Business\Exception\UserNotFoundException');
        $this->authFacade->requestPasswordReset('username1@domain.tld');
    }

    public function testResetPassword()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset('username@domain.tld');

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUserId($userEntity->getIdUser());

        $resetStatus = $this->authFacade->resetPassword($passwordEntity->getCode(), 'new');

        $passwordEntity->reload();

        $this->assertTrue($resetStatus);
        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_USED);
    }

    public function testValidateExpiredResetToken()
    {
        $userEntity = $this->createTestUser();
        $userEntity->save();

        $this->authFacade->requestPasswordReset('username@domain.tld');

        $userEntity->reload();

        $passwordEntity = SpyResetPasswordQuery::create()->findOneByFkUserId($userEntity->getIdUser());
        $expiredDateTime = new \DateTime('last year');
        $passwordEntity->setCreatedAt($expiredDateTime);
        $passwordEntity->save();

        $resetStatus = $this->authFacade->isValidPasswordResetToken($passwordEntity->getCode());

        $passwordEntity->reload();

        $this->assertEquals($passwordEntity->getStatus(), SpyResetPasswordTableMap::COL_STATUS_EXPIRED);
        $this->assertFalse($resetStatus);
    }

    public function testValidateNonExistantResetToken()
    {
        $resetStatus = $this->authFacade->isValidPasswordResetToken('NERAMANES');
        $this->assertFalse($resetStatus);
    }

    protected function createTestUser()
    {
        $userEntity = new SpyUser();
        $userEntity->setUsername('username@domain.tld');
        $userEntity->setFirstName('FirstName');
        $userEntity->setLastName('LastName');
        $userEntity->setPassword('Secret');
        $userEntity->setStatus(0);

        return $userEntity;
    }
}
