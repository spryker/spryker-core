<?php
namespace Functional\SprykerFeature\Zed\Auth;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\Auth\Business\AuthSettings;
use SprykerFeature\Zed\User\Business\UserFacade;
use SprykerFeature\Zed\Auth\Business\Client\StaticToken;
use SprykerEngine\Zed\Kernel\Business\Factory;

/**
 * @group AuthTest
 */
class AuthTest extends Test
{
    /**
     * @var AuthFacade $authFacade
     */
    private $authFacade;

    /**
     * @var UserFacade $userFacade
     */
    private $userFacade;

    /**
     * @var AutoCompletion
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->userFacade = new UserFacade(
            new Factory('User'),
            $this->locator
        );

        $this->authFacade = new AuthFacade(
            new Factory('Auth'),
            $this->locator
        );
    }

    /**
     * @return array
     */
    private function mockUserData()
    {
        $data['firstName'] = sprintf('Test-%s', rand(100, 999));
        $data['lastName'] = sprintf('LastName-%s', rand(100, 999));
        $data['username'] = sprintf('Username-%s', rand(100, 999));
        $data['password'] = sprintf('Password-%s', rand(100, 999));

        return $data;
    }

    /**
     * @param $data
     * @return NULL|\SprykerFeature\Shared\User\Transfer\User
     */
    private function mockAddUser($data)
    {
        return $this->userFacade->addUser($data['firstName'], $data['lastName'], $data['username'], $data['password']);
    }

    /**
     * @group Auth
     */
    public function testUserToken()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $token = $this->authFacade->getUserToken($userDto);
        $fakeToken = md5(sprintf('%s%s', $userDto->getPassword(), $userDto->getIdUserUser()));

        $this->assertEquals($fakeToken, $token);

        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);

        $isValid = $this->authFacade->isAuthorized($token);
        $this->assertTrue($isValid);
    }

    /**
     * @group Auth
     */
    public function testIgnorablePath()
    {
        $ignorable = $this->authFacade->isIgnorable('auth', 'login', 'index');
        $this->assertTrue($ignorable);

        $ignorable = $this->authFacade->isIgnorable('auth', 'login', 'check');
        $this->assertTrue($ignorable);
    }

    /**
     * @group Auth
     */
    public function testDoLogin()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);
    }

    /**
     * @group Auth
     */
    public function testLoginNotAllowed()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $userDto);
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
     * @group Auth
     */
    public function testDoLoginWithToken()
    {
        $settings = new AuthSettings($this->locator);
        $token = new StaticToken();
        $credentials = $settings->getUsersCredentials();

        foreach ($credentials as $username => $credential) {
            $token->setRawToken($credential['token']);
            $hash = $token->generate();
            $isAllowed = $this->authFacade->isAuthorized($hash);
            $this->assertTrue($isAllowed);
        }
    }

    /**
     * @group Auth
     */
    public function testDenyLoginWithWrongToken()
    {
        $token = new StaticToken();

        $token->setRawToken('WRONGTOKEN');
        $hash = $token->generate();
        $isAllowed = $this->authFacade->isAuthorized($hash);
        $this->assertTrue(!$isAllowed);
    }

    /**
     * @group Auth
     */
    public function testCheckDoLoginAndCurrentUserIsTheSame()
    {
        $userData = $this->mockUserData();
        $userDto = $this->mockAddUser($userData);

        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $userDto);
        $this->assertNotEquals($userData['password'], $userDto->getPassword());

        $login = $this->authFacade->login($userDto->getUsername(), $userData['password']);
        $this->assertTrue($login);

        $currentUserDto = $this->userFacade->getCurrentUser();
        $this->assertInstanceOf('\SprykerFeature\Shared\User\Transfer\User', $currentUserDto);
        $this->assertEquals($userDto->getIdUserUser(), $currentUserDto->getIdUserUser());
        $this->assertEquals($userDto->getUsername(), $currentUserDto->getUsername());
        $this->assertEquals($userDto->getPassword(), $currentUserDto->getPassword());
        $this->assertEquals($userDto->getFirstName(), $currentUserDto->getFirstName());
        $this->assertEquals($userDto->getLastName(), $currentUserDto->getLastName());
    }
}
