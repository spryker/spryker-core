<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Communication\Plugin\Application;

use Codeception\Test\Unit;
use LogicException;
use ReflectionClass;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfigurator;
use Spryker\Zed\Security\Communication\Plugin\Application\ZedSecurityApplicationPlugin;
use SprykerTest\Zed\Security\Fixtures\DefaultAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Security
 * @group Communication
 * @group Plugin
 * @group Application
 * @group ZedSecurityApplicationPluginTest
 * Add your own group annotations below this line
 */
class ZedSecurityApplicationPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_FIREWALL = 'security.firewall';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_LAST_ERROR = 'security.last_error';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var string
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @var string
     */
    protected const SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR = 'security.default.login_form.authenticator';

    /**
     * @var string
     */
    protected const HOMEPAGE_PATH = '/homepage';

    /**
     * @var string
     */
    protected const USER_PAGE_PATH = '/homepage/user';

    /**
     * @var string
     */
    protected const LOGIN_PATH = '/login';

    /**
     * @var string
     */
    protected const USER_NAME = 'user';

    /**
     * @var string
     */
    protected const USER_PASSWORD = 'foo';

    /**
     * @var string
     */
    protected const USER_ENCODED_PASSWORD = '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu';

    /**
     * @var string
     */
    protected const USER_INVALID_PASSWORD = 'bar';

    /**
     * @var string
     */
    protected const ROLE_USER = 'ROLE_USER';

    /**
     * @var string
     */
    protected const USER = 'USER';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    /**
     * @var string
     */
    protected const IS_AUTHENTICATED_FULLY = 'IS_AUTHENTICATED_FULLY';

    /**
     * @var string
     */
    protected const AUTHENTICATED = 'AUTHENTICATED';

    /**
     * @var string
     */
    protected const HTTP_AUTH_FIREWALL_NAME = 'http-auth';

    /**
     * @var \SprykerTest\Zed\Security\SecurityCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->tester->isSymfonyVersion5() === true) {
            $this->markTestSkipped('Compatible only with `symfony/security-core` package version >= 6. Will be enabled by default once Symfony 5 support is discontinued.');
        }
    }

    /**
     * @return void
     */
    public function testProvideAddsAllServices(): void
    {
        // Arrange
        $container = $this->tester->getContainer();
        $securityApplicationPlugin = new ZedSecurityApplicationPlugin();

        // Act
        $securityApplicationPlugin->provide($container);

        // Assert
        $this->assertTrue($container->has(static::SERVICE_SECURITY_FIREWALL));
    }

    /**
     * @group testWrongAuthenticationType
     *
     * @return void
     */
    public function testWrongAuthenticationType(): void
    {
        // Arrange
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('wrong', [
            'foobar' => true,
            'users' => [],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function (): void {
        });

        // Assert
        $this->expectException(LogicException::class);

        // Act
        $this->tester->getKernel()->handle(Request::create(static::HOMEPAGE_PATH));
    }

    /**
     * @return void
     */
    public function testFormAuthentication(): void
    {
        //Arrange
        $this->addFormAuthentication();
        $container = $this->tester->getContainer();

        //Act
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);

        //Assert
        $this->assertSame(static::ACCESS_MODE_PUBLIC, $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['loginForm' => ['username' => static::USER_NAME, 'password' => static::USER_INVALID_PASSWORD]]);
        $this->assertSame(403, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertSame('{"message":"Invalid credentials."}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->getRequest()->getSession()->start();

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['loginForm' => ['username' => static::USER_NAME, 'password' => static::USER_PASSWORD]]);
        $this->assertNull($container->get(static::SERVICE_SECURITY_LAST_ERROR)($httpKernelBrowser->getRequest()));

        $httpKernelBrowser->getRequest()->getSession()->save();

        $this->assertSame(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/', $httpKernelBrowser->getResponse()->getTargetUrl());
    }

    /**
     * @return void
     */
    public function testHttpAuthentication(): void
    {
        //Arrange
        $this->addHttpAuthentication();

        //Act
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);

        //Assert
        $this->assertSame(401, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertSame('Basic realm="Secured"', $httpKernelBrowser->getResponse()->headers->get('www-authenticate'));

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH, [], [], ['PHP_AUTH_USER' => static::USER_NAME, 'PHP_AUTH_PW' => static::USER_PASSWORD]);
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);

        $this->assertSame('userAUTHENTICATEDUSER', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    protected function addFormAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('user', [
                'pattern' => '^' . static::USER_PAGE_PATH,
            ])
            ->addFirewall('default', [
                'pattern' => '^' . static::HOMEPAGE_PATH,
                'form' => [
                    'require_previous_session' => false,
                    'login_path' => static::HOMEPAGE_PATH . static::LOGIN_PATH,
                    'check_path' => static::HOMEPAGE_PATH . '/login_check',
                    'authenticators' => [
                        static::SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR,
                    ],
                ],
                'logout' => [
                    'logout_path' => static::HOMEPAGE_PATH . '/logout',
                    'target_url' => static::HOMEPAGE_PATH,
                ],
                'users' => [
                    static::USER_NAME => [static::ROLE_USER, static::USER_ENCODED_PASSWORD],
                ],
            ])
            ->addAccessRules([['^' . static::USER_PAGE_PATH, static::ROLE_USER]]);

        $this->tester->mockZedSecurityPlugin($securityConfiguration);
        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();

        $container = $this->tester->getContainer();
        $container->set(static::SECURITY_DEFAULT_LOGIN_FORM_AUTHENTICATOR, function () {
            return new DefaultAuthenticator();
        });

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () use ($container) {
            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token ? $token->getUser()->getUserIdentifier() : static::ACCESS_MODE_PUBLIC;

            return new Response($content);
        });

        $this->tester->addRoute('login', static::LOGIN_PATH, function (Request $request) use ($container) {
            $container->get(static::SERVICE_SESSION)->start();
            $securityLastError = $container->get(static::SERVICE_SECURITY_LAST_ERROR);

            return $securityLastError($request);
        });

        $this->tester->addRoute('user', static::USER_PAGE_PATH, function (Request $request) use ($container) {
            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token ? $token->getUser()->getUserIdentifier() : static::ACCESS_MODE_PUBLIC;

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::IS_AUTHENTICATED_FULLY)) {
                $content .= static::AUTHENTICATED;
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::ROLE_USER)) {
                $content .= static::USER;
            }

            return new Response($content);
        });
    }

    /**
     * @return void
     */
    protected function addHttpAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall(static::HTTP_AUTH_FIREWALL_NAME, [
                'pattern' => '^' . static::HOMEPAGE_PATH,
                'http' => true,
                'users' => [
                    static::USER_NAME => [static::ROLE_USER, static::USER_ENCODED_PASSWORD],
                ],
            ])
            ->addAccessRules([['^' . static::HOMEPAGE_PATH, static::ROLE_USER]]);

        $this->tester->mockZedSecurityPlugin($securityConfiguration);
        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            $container = $this->tester->getContainer();
            if (!$container->has(static::SERVICE_SECURITY_TOKEN_STORAGE)) {
                return new Response(static::ACCESS_MODE_PUBLIC);
            }

            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token->getUser()->getUserIdentifier();

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::IS_AUTHENTICATED_FULLY)) {
                $content .= static::AUTHENTICATED;
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::ROLE_USER)) {
                $content .= static::USER;
            }

            return new Response($content);
        });
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $reflection = new ReflectionClass(SecurityConfigurator::class);
        $property = $reflection->getProperty('securityConfiguration');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
