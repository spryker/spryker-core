<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Exception;
use LogicException;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin;
use SprykerTest\Zed\Security\Fixtures\TokenAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Security
 * @group Communication
 * @group Plugin
 * @group Application
 * @group SecurityApplicationPluginTest
 * Add your own group annotations below this line
 */
class SecurityApplicationPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_FIREWALL
     */
    protected const SERVICE_SECURITY_FIREWALL = 'security.firewall';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_LAST_ERROR
     */
    protected const SERVICE_SECURITY_LAST_ERROR = 'security.last_error';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_USER_PROVIDER_INMEMORY_PROTO
     */
    protected const SERVICE_SECURITY_USER_PROVIDER_INMEMORY_PROTO = 'security.user_provider.inmemory._proto';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_ACCESS_MAP
     */
    protected const SERVICE_SECURITY_ACCESS_MAP = 'security.access_map';

    protected const HOMEPAGE_PATH = '/homepage';
    protected const USER_PAGE_PATH = '/homepage/user';
    protected const LOGIN_PATH = '/login';

    protected const USER_NAME = 'user';
    protected const USER_PASSWORD = 'foo';
    protected const USER_ENCODED_PASSWORD = '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu';
    protected const USER_INVALID_PASSWORD = 'bar';
    protected const ROLE_USER = 'ROLE_USER';

    /**
     * @var \SprykerTest\Zed\Security\SecurityCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->tester->enableSecurityApplicationPlugin();
    }

    /**
     * @return void
     */
    public function testProvideAddsAllServices(): void
    {
        // Arrange
        $container = $this->tester->getContainer();
        $securityApplicationPlugin = new SecurityApplicationPlugin();

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
        $this->addFormAuthentication();

        $container = $this->tester->getContainer();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['_username' => static::USER_NAME, '_password' => static::USER_INVALID_PASSWORD]);
        $this->assertStringContainsString('Bad credentials', $container->get(static::SERVICE_SECURITY_LAST_ERROR)($httpKernelBrowser->getRequest()));
        // hack to re-close the session as the previous assertions re-opens it
        $httpKernelBrowser->getRequest()->getSession()->save();

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['_username' => static::USER_NAME, '_password' => static::USER_PASSWORD]);
        $this->assertNull($container->get(static::SERVICE_SECURITY_LAST_ERROR)($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();
        $this->assertSame(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/', $httpKernelBrowser->getResponse()->getTargetUrl());

        $httpKernelBrowser->request('get', static::USER_PAGE_PATH);
        $this->assertSame('userAUTHENTICATEDUSER', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testHttpAuthentication(): void
    {
        $this->addHttpAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame(401, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertSame('Basic realm="Secured"', $httpKernelBrowser->getResponse()->headers->get('www-authenticate'));

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH, [], [], ['PHP_AUTH_USER' => static::USER_NAME, 'PHP_AUTH_PW' => static::USER_PASSWORD]);
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame('userAUTHENTICATEDUSER', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testGuardAuthentication(): void
    {
        $this->addGuardAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame(401, $httpKernelBrowser->getResponse()->getStatusCode(), 'The entry point is configured');
        $this->assertSame('{"message":"Authentication Required"}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH, [], [], ['HTTP_X_AUTH_TOKEN' => sprintf('%s:%s', 'customer', static::USER_INVALID_PASSWORD)]);
        $this->assertSame(403, $httpKernelBrowser->getResponse()->getStatusCode(), 'User not found');
        $this->assertSame('{"message":"Username could not be found."}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH, [], [], ['HTTP_X_AUTH_TOKEN' => sprintf('%s:%s', static::USER_NAME, static::USER_INVALID_PASSWORD)]);
        $this->assertSame(403, $httpKernelBrowser->getResponse()->getStatusCode(), 'Invalid credentials');
        $this->assertSame('{"message":"Invalid credentials."}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH, [], [], ['HTTP_X_AUTH_TOKEN' => sprintf('%s:%s', static::USER_NAME, static::USER_PASSWORD)]);
        $this->assertSame(static::USER_NAME, $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testWithOwnAuthenticationSuccessHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'form' => [
                'require_previous_session' => false,
            ],
            'users' => [
                static::USER_NAME => [static::ROLE_USER, static::USER_ENCODED_PASSWORD],
            ],
        ]);
        $securityConfiguration->addAuthenticationSuccessHandler('default', function () {
            return new class implements AuthenticationSuccessHandlerInterface
            {
                /**
                 * @param \Symfony\Component\HttpFoundation\Request $request
                 * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
                 *
                 * @return \Symfony\Component\HttpFoundation\Response
                 */
                public function onAuthenticationSuccess(Request $request, TokenInterface $token)
                {
                    return new Response('authentication success');
                }
            };
        });
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('post', '/login_check', ['_username' => static::USER_NAME, '_password' => static::USER_PASSWORD]);
        $this->assertSame('authentication success', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @group single
     *
     * @return void
     */
    public function testWithOwnAuthenticationErrorHandler(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'form' => [
                'require_previous_session' => false,
            ],
            'users' => [
                static::USER_NAME => [static::ROLE_USER, static::USER_ENCODED_PASSWORD],
            ],
        ]);
        $securityConfiguration->addAuthenticationFailureHandler('default', function () {
            return new class implements AuthenticationFailureHandlerInterface
            {
                /**
                 * @param \Symfony\Component\HttpFoundation\Request $request
                 * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
                 *
                 * @return \Symfony\Component\HttpFoundation\Response
                 */
                public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
                {
                    return new Response('authentication failure');
                }
            };
        });
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'not existing', '_password' => 'not existsing']);

        $this->assertSame('authentication failure', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testFirewallWithMethod(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'pattern' => static::HOMEPAGE_PATH,
            'http' => true,
            'methods' => ['POST'],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);
        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        }, [], [], [], null, null, ['POST', 'GET']);

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame(200, $httpKernelBrowser->getResponse()->getStatusCode());

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH);
        $this->assertSame(401, $httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFirewallWithHost(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'pattern' => static::HOMEPAGE_PATH,
            'http' => true,
            'hosts' => 'localhost2',
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);
        $this->tester->addRoute('homepage-1', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        }, [], [], [], 'localhost1');
        $this->tester->addRoute('homepage-2', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        }, [], [], [], 'localhost2');

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', 'http://localhost2' . static::HOMEPAGE_PATH);
        $this->assertSame(401, $httpKernelBrowser->getResponse()->getStatusCode());

        $httpKernelBrowser->request('get', 'http://localhost1' . static::HOMEPAGE_PATH);
        $this->assertSame(200, $httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUserWithNoToken(): void
    {
        $container = $this->tester->getContainer();
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return void
     */
    public function testUserWithInvalidUser(): void
    {
        $container = $this->tester->getContainer();
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->getContainer()->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->setToken(new UsernamePasswordToken('foo', 'foo', 'foo'));
        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return void
     */
    public function testAccessRulePathArray(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('default', [
                'http' => true,
            ])
            ->addAccessRules([[['path' => '^' . static::HOMEPAGE_PATH], static::ROLE_USER]]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        // This will boot the application
        $this->tester->getHttpKernelBrowser();

        $request = Request::create(static::HOMEPAGE_PATH);
        $accessMap = $container->get(static::SERVICE_SECURITY_ACCESS_MAP);
        $this->assertEquals($accessMap->getPatterns($request), [
            [static::ROLE_USER],
            '',
        ]);
    }

    /**
     * @return void
     */
    public function testExposedExceptions(): void
    {
        $container = $this->tester->getContainer();
        $this->tester->mockConfigMethod('hideUserNotFoundException', function () {
            return false;
        });
        $this->addFormAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', static::HOMEPAGE_PATH);
        $this->assertSame('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['_username' => static::USER_NAME, '_password' => static::USER_INVALID_PASSWORD]);
        $this->assertSame('The presented password is invalid.', $container->get(static::SERVICE_SECURITY_LAST_ERROR)($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();

        $httpKernelBrowser->request('post', static::HOMEPAGE_PATH . '/login_check', ['_username' => 'unknown', '_password' => static::USER_INVALID_PASSWORD]);
        $this->assertSame('Username "unknown" does not exist.', $container->get(static::SERVICE_SECURITY_LAST_ERROR)($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();
    }

    /**
     * @return void
     */
    public function testLastErrorReturnSecurityAuthenticationError(): void
    {
        // Arrange
        $container = $this->tester->getContainer();
        $securityApplicationPlugin = new SecurityApplicationPlugin();
        $container = $securityApplicationPlugin->provide($container);

        // Act
        $request = Request::create(static::HOMEPAGE_PATH);
        $request->attributes->set(Security::AUTHENTICATION_ERROR, new Exception('security error'));
        $securityError = $container->get(static::SERVICE_SECURITY_LAST_ERROR)($request);

        // Assert
        $this->assertSame('security error', $securityError);
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
                'anonymous' => true,
                'form' => [
                    'require_previous_session' => false,
                    'login_path' => static::HOMEPAGE_PATH . static::LOGIN_PATH,
                    'check_path' => static::HOMEPAGE_PATH . '/login_check',
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

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () use ($container) {
            /** @var \Generated\Shared\Transfer\UserTransfer $user */
            $user = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken()->getUser();
            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            return new Response($content);
        });

        $this->tester->addRoute('login', static::LOGIN_PATH, function (Request $request) use ($container) {
            $container->get('session')->start();
            $securityLastError = $container->get(static::SERVICE_SECURITY_LAST_ERROR);

            return $securityLastError($request);
        });

        $this->tester->addRoute('user', static::USER_PAGE_PATH, function (Request $request) use ($container) {
            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token ? $token->getUser()->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::ROLE_USER)) {
                $content .= 'USER';
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
            ->addFirewall('http-auth', [
                'pattern' => '^' . static::HOMEPAGE_PATH,
                'http' => true,
                'users' => [
                    static::USER_NAME => [static::ROLE_USER, static::USER_ENCODED_PASSWORD],
                ],
            ])
            ->addAccessRules([['^' . static::HOMEPAGE_PATH, static::ROLE_USER]]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () {
            $container = $this->tester->getContainer();
            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token ? $token->getUser()->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted(static::ROLE_USER)) {
                $content .= 'USER';
            }

            return new Response($content);
        });
    }

    /**
     * @return void
     */
    protected function addGuardAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('guard', [
                'pattern' => '^.*$',
                'form' => true,
                'guard' => [
                    'authenticators' => [
                        'app.authenticator.token',
                    ],
                ],
                'users' => [
                    static::USER_NAME => [static::ROLE_USER, static::USER_PASSWORD],
                ],
            ]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        $this->tester->addRoute('homepage', static::HOMEPAGE_PATH, function () use ($container) {
            $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
            $content = $token ? $token->getUser()->getUsername() : 'ANONYMOUS';

            return new Response($content);
        });

        $container->set('app.authenticator.token', function () {
            return new TokenAuthenticator();
        });
    }
}
