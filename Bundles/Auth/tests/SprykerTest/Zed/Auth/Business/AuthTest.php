<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Auth\Business;

use Codeception\Test\Unit;
use Spryker\Client\Session\SessionClient;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\AuthFacade;
use Spryker\Zed\Auth\Business\Client\StaticToken;
use Spryker\Zed\User\Business\UserFacade;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Auth
 * @group Business
 * @group AuthTest
 * Add your own group annotations below this line
 */
class AuthTest extends Unit
{
    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacade
     */
    protected $authFacade;

    /**
     * @var \Spryker\Zed\User\Business\UserFacade
     */
    protected $userFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $sessionClient = new SessionClient();
        $sessionClient->setContainer(new Session(new MockArraySessionStorage()));

        $this->userFacade = new UserFacade();
        $this->authFacade = new AuthFacade();
    }

    /**
     * @return string[]
     */
    private function mockUserData()
    {
        $data = [];

        $data['firstName'] = sprintf('Test-%s', rand(100, 999));
        $data['lastName'] = sprintf('LastName-%s', rand(100, 999));
        $data['username'] = sprintf('Username-%s', rand(100, 999));
        $data['password'] = sprintf('Password-%s', rand(100, 999));

        return $data;
    }

    /**
     * @param string[] $data
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    private function mockAddUser($data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @return void
     */
    public function testUserToken()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $token = $this->authFacade->getUserToken($userDto);
        $fakeToken = hash('sha256', sprintf('%s%s', $userDto->getPassword(), $userDto->getIdUser()));

        $this->assertEquals($fakeToken, $token);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);

        $isValid = $this->authFacade->isAuthenticated($token);
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIgnorablePath()
    {
        $ignorable = $this->authFacade->isIgnorable('auth', 'login', 'index');
        $this->assertTrue($ignorable);

        $ignorable = $this->authFacade->isIgnorable('auth', 'login', 'check');
        $this->assertTrue($ignorable);
    }

    /**
     * @return void
     */
    public function testDoLogin()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);
    }

    /**
     * @return void
     */
    public function testLoginNotAllowed()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), rand(10000, 99999));
        $this->assertEquals(false, $login);

        $login = $this->authFacade->login($userDto->getUsername(), $userDto->getPassword());
        $this->assertEquals(false, $login);

        $login = $this->authFacade->login(rand(10000, 99999), $userDto->getPassword());
        $this->assertEquals(false, $login);

        $login = $this->authFacade->login(rand(10000, 99999), $userData['password']);
        $this->assertEquals(false, $login);

        $login = $this->authFacade->login(rand(10000, 99999), rand(10000, 99999));
        $this->assertEquals(false, $login);
    }

    /**
     * @return void
     */
    public function testDoLoginWithToken()
    {
        $settings = new AuthConfig();
        $token = new StaticToken();
        $credentials = $settings->getUsersCredentials();

        foreach ($credentials as $username => $credential) {
            $token->setRawToken($credential['token']);
            $hash = $token->generate();
            $isAllowed = $this->authFacade->isAuthenticated($hash);
            $this->assertTrue($isAllowed);
        }
    }

    /**
     * @return void
     */
    public function testDenyLoginWithWrongToken()
    {
        $token = new StaticToken();

        $token->setRawToken('WRONGTOKEN');
        $hash = $token->generate();
        $isAllowed = $this->authFacade->isAuthenticated($hash);
        $this->assertTrue(!$isAllowed);
    }

    /**
     * @return void
     */
    public function testCheckDoLoginAndCurrentUserIsTheSame()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);

        $currentUserDto = $this->userFacade->getCurrentUser();
        $this->assertInstanceOf('\Generated\Shared\Transfer\UserTransfer', $currentUserDto);
        $this->assertEquals($userDto->getIdUser(), $currentUserDto->getIdUser());
        $this->assertEquals($userDto->getUsername(), $currentUserDto->getUsername());
        $this->assertEquals($userDto->getPassword(), $currentUserDto->getPassword());
        $this->assertEquals($userDto->getFirstName(), $currentUserDto->getFirstName());
        $this->assertEquals($userDto->getLastName(), $currentUserDto->getLastName());
    }

    /**
     * @return void
     */
    public function testIsAuthorizedWithYvesCredentialsFromConfigMustReturnTrue()
    {
        $token = new StaticToken();

        $authConfig = Config::get(AuthConstants::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        $token->setRawToken($rawToken);
        $hash = $token->generate();

        $isAllowed = $this->authFacade->isAuthenticated($hash);
        $this->assertTrue($isAllowed);
    }
}
