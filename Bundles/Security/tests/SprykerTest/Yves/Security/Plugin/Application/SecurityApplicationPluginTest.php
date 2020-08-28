<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security\Plugin\Application;

use Codeception\Test\Unit;
use Exception;
use LogicException;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin;
use SprykerTest\Yves\Security\Fixtures\TokenAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Security
 * @group Plugin
 * @group Application
 * @group SecurityApplicationPluginTest
 * Add your own group annotations below this line
 */
class SecurityApplicationPluginTest extends Unit
{
    /**
     * @uses \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

    /**
     * @var \SprykerTest\Yves\Security\SecurityTester
     */
    protected $tester;

    /**
     * @var \Spryker\Yves\Security\Plugin\Application\SecurityApplicationPlugin|null
     */
    protected $securityApplicationPlugin;

    /**
     * @return void
     */
    public function testProvideAddsAllServices(): void
    {
        $container = $this->tester->getContainer();
        $securityApplicationPlugin = new SecurityApplicationPlugin();
        $securityApplicationPlugin->provide($container);

        $this->assertTrue($container->has('security.firewall'));
    }

    /**
     * @group testWrongAuthenticationType
     *
     * @return void
     */
    public function testWrongAuthenticationType(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('wrong', [
            'foobar' => true,
            'users' => [],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/', function (): void {
        });

        $this->expectException(LogicException::class);
        $this->tester->getKernel()->handle(Request::create('/'));
    }

    /**
     * @return void
     */
    public function testFormAuthentication(): void
    {
        $this->addFormAuthentication();

        $container = $this->tester->getContainer();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'user', '_password' => 'bar']);
        $this->assertStringContainsString('Bad credentials', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        // hack to re-close the session as the previous assertions re-opens it
        $httpKernelBrowser->getRequest()->getSession()->save();

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'user', '_password' => 'foo']);
        $this->assertEquals('', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();
        $this->assertEquals(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/', $httpKernelBrowser->getResponse()->getTargetUrl());

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('userAUTHENTICATED', $httpKernelBrowser->getResponse()->getContent());

        $this->expectException(AccessDeniedHttpException::class);
        $httpKernelBrowser->request('get', '/admin');

        $httpKernelBrowser->request('get', '/logout');
        $this->assertEquals(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/', $httpKernelBrowser->getResponse()->getTargetUrl());

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/admin');
        $this->assertEquals(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/login', $httpKernelBrowser->getResponse()->getTargetUrl());

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'admin', '_password' => 'foo']);
        $this->assertEquals('', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();
        $this->assertEquals(302, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/admin', $httpKernelBrowser->getResponse()->getTargetUrl());

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('adminAUTHENTICATEDADMIN', $httpKernelBrowser->getResponse()->getContent());
        $httpKernelBrowser->request('get', '/admin');
        $this->assertEquals('admin', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testHttpAuthentication(): void
    {
        $this->addHttpAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals(401, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('Basic realm="Secured"', $httpKernelBrowser->getResponse()->headers->get('www-authenticate'));

        $httpKernelBrowser->request('get', '/', [], [], ['PHP_AUTH_USER' => 'user', 'PHP_AUTH_PW' => 'foo']);
        $this->assertEquals('userAUTHENTICATED', $httpKernelBrowser->getResponse()->getContent());

        $this->expectException(AccessDeniedHttpException::class);
        $httpKernelBrowser->request('get', '/admin');

        $httpKernelBrowser->restart();

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals(401, $httpKernelBrowser->getResponse()->getStatusCode());
        $this->assertEquals('Basic realm="Secured"', $httpKernelBrowser->getResponse()->headers->get('www-authenticate'));

        $httpKernelBrowser->request('get', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'foo']);
        $this->assertEquals('adminAUTHENTICATEDADMIN', $httpKernelBrowser->getResponse()->getContent());
        $httpKernelBrowser->request('get', '/admin');
        $this->assertEquals('admin', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testGuardAuthentication(): void
    {
        $this->addGuardAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertEquals(401, $httpKernelBrowser->getResponse()->getStatusCode(), 'The entry point is configured');
        $this->assertEquals('{"message":"Authentication Required"}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/', [], [], ['HTTP_X_AUTH_TOKEN' => 'lili:not the secret']);
        $this->assertEquals(403, $httpKernelBrowser->getResponse()->getStatusCode(), 'User not found');
        $this->assertEquals('{"message":"Username could not be found."}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/', [], [], ['HTTP_X_AUTH_TOKEN' => 'victoria:not the secret']);
        $this->assertEquals(403, $httpKernelBrowser->getResponse()->getStatusCode(), 'Invalid credentials');
        $this->assertEquals('{"message":"Invalid credentials."}', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/', [], [], ['HTTP_X_AUTH_TOKEN' => 'victoria:victoriasecret']);
        $this->assertEquals('victoria', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @group switchUser
     *
     * @return void
     */
    public function testSwitchUser(): void
    {
        $this->addSwitchUserAuthentication();

        $container = $this->tester->getContainer();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'admin', '_password' => 'foo']);
        $this->assertEquals('', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('adminAUTHENTICATEDADMIN', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/?_switch_user=user');
        $this->assertEquals('userAUTHENTICATED', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('get', '/?_switch_user=_exit');
        $this->assertEquals('adminAUTHENTICATEDADMIN', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    protected function addSwitchUserAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('login', [
                'pattern' => '^/login$',
            ])
            ->addFirewall('default', [
                'pattern' => '^.*$',
                'anonymous' => true,
                'form' => [
                    'require_previous_session' => false,
                ],
                'logout' => true,
                'users' => [
                    // password is foo
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                    'admin' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
                'switch_user' => true,
                'stateless' => true,
            ])
            ->addAccessRules([['^/admin', 'ROLE_ADMIN']])
            ->addRoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        $this->tester->addRoute('homepage', '/', function () use ($container) {
            $user = $container->get('security.token_storage')->getToken()->getUser();

            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('ROLE_ADMIN')) {
                $content .= 'ADMIN';
            }

            return new Response($content);
        });
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
                'user' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
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

        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'user', '_password' => 'foo']);
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
                'user' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
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

        $this->tester->addRoute('homepage', '/', function () {
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
            'pattern' => '/',
            'http' => true,
            'methods' => ['POST'],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);
        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        }, [], [], [], null, null, ['POST', 'GET']);

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertEquals(200, $httpKernelBrowser->getResponse()->getStatusCode());

        $httpKernelBrowser->request('post', '/');
        $this->assertEquals(401, $httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function testFirewallWithHost(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'pattern' => '/',
            'http' => true,
            'hosts' => 'localhost2',
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);
        $this->tester->addRoute('homepage-1', '/', function () {
            return new Response('foo');
        }, [], [], [], 'localhost1');
        $this->tester->addRoute('homepage-2', '/', function () {
            return new Response('foo');
        }, [], [], [], 'localhost2');

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', 'http://localhost2/');
        $this->assertEquals(401, $httpKernelBrowser->getResponse()->getStatusCode());

        $httpKernelBrowser->request('get', 'http://localhost1/');
        $this->assertEquals(200, $httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUser(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
            'users' => [
                'user' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
            ],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        });

        $container = $this->tester->getContainer();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertNull($container->get('user'));

        $httpKernelBrowser->request('get', '/', [], [], [
            'HTTP_PHP_AUTH_USER' => 'user',
            'HTTP_PHP_AUTH_PW' => 'foo',
        ]);
        $this->assertInstanceOf(UserInterface::class, $container->get('user'));
        $this->assertEquals('user', $container->get('user')->getUsername());
    }

    /**
     * @return void
     */
    public function testUserAsServiceString(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
            'users' => 'my_user_provider',
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);
        $users = [
            'user' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
        ];

        $container = $this->tester->getContainer();
        $container->set('my_user_provider', $container->get('security.user_provider.inmemory._proto')($users));

        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertNull($container->get('user'));
        $this->assertSame($container->get('my_user_provider'), $container->get('security.user_provider.default'));

        $httpKernelBrowser->request('get', '/', [], [], [
            'HTTP_PHP_AUTH_USER' => 'user',
            'HTTP_PHP_AUTH_PW' => 'foo',
        ]);
        $this->assertInstanceOf(UserInterface::class, $container->get('user'));
        $this->assertEquals('user', $container->get('user')->getUsername());
    }

    /**
     * @return void
     */
    public function testUserWithNoToken(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertNull($this->tester->getContainer()->get('user'));
    }

    /**
     * @return void
     */
    public function testUserWithInvalidUser(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('default', [
            'http' => true,
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->getContainer()->get('security.token_storage')->setToken(new UsernamePasswordToken('foo', 'foo', 'foo'));
        $this->tester->addRoute('homepage', '/', function () {
            return new Response('foo');
        });

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $httpKernelBrowser->request('get', '/');
        $this->assertNull($this->tester->getContainer()->get('user'));
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
            ->addAccessRules([[['path' => '^/admin'], 'ROLE_ADMIN']]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        // This will boot the application
        $this->tester->getHttpKernelBrowser();

        $request = Request::create('/admin');
        $accessMap = $container->get('security.access_map');
        $this->assertEquals($accessMap->getPatterns($request), [
            ['ROLE_ADMIN'],
            '',
        ]);
    }

    /**
     * @return void
     */
    public function testUserPasswordValidatorIsRegistered(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration->addFirewall('admin', [
            'pattern' => '^/admin',
            'http' => true,
            'users' => [
                'admin' => ['ROLE_ADMIN', '513aeb0121909'],
            ],
        ]);
        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();
        $container->set('validator', true);
        $container->set('validator.validator_service_ids', []);

        // This will boot the application
        $this->tester->getHttpKernelBrowser();

        $this->assertInstanceOf(UserPasswordValidator::class, $container->get('security.validator.user_password_validator'));
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

        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('ANONYMOUS', $httpKernelBrowser->getResponse()->getContent());

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'user', '_password' => 'bar']);
        $this->assertEquals('The presented password is invalid.', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'unknown', '_password' => 'bar']);
        $this->assertEquals('Username "unknown" does not exist.', $container->get('security.last_error')($httpKernelBrowser->getRequest()));
        $httpKernelBrowser->getRequest()->getSession()->save();
    }

    /**
     * @return void
     */
    public function testLastErrorReturnSecurityAuthenticationError(): void
    {
        $container = $this->tester->getContainer();
        $securityApplicationPlugin = new SecurityApplicationPlugin();
        $container = $securityApplicationPlugin->provide($container);

        $request = Request::create('/');
        $request->attributes->set(Security::AUTHENTICATION_ERROR, new Exception('security error'));
        $securityError = $container->get('security.last_error')($request);

        $this->assertSame('security error', $securityError);
    }

    /**
     * @return void
     */
    protected function addFormAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('login', [
                'pattern' => '^/login$',
            ])
            ->addFirewall('default', [
                'pattern' => '^.*$',
                'anonymous' => true,
                'form' => [
                    'require_previous_session' => false,
                ],
                'logout' => true,
                'users' => [
                    // password is foo
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                    'admin' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
            ])
            ->addAccessRules([['^/admin', 'ROLE_ADMIN']])
            ->addRoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();
        $this->tester->addRoute('login', '/login', function (Request $request) use ($container) {
            $container->get('session')->start();
            $securityLastError = $container->get('security.last_error');

            return $securityLastError($request);
        });

        $this->tester->addRoute('homepage', '/', function () use ($container) {
            $user = $container->get('security.token_storage')->getToken()->getUser();

            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('ROLE_ADMIN')) {
                $content .= 'ADMIN';
            }

            return new Response($content);
        });

        $this->tester->addRoute('admin', '/admin', function () {
            return 'admin';
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
                'pattern' => '^.*$',
                'http' => true,
                'users' => [
                    // password is foo
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                    'admin' => ['ROLE_ADMIN', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
            ])
            ->addAccessRules([['^/admin', 'ROLE_ADMIN']])
            ->addRoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/', function () {
            $container = $this->tester->getContainer();
            $user = $container->get('security.token_storage')->getToken()->getUser();
            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('IS_AUTHENTICATED_FULLY')) {
                $content .= 'AUTHENTICATED';
            }

            if ($container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)->isGranted('ROLE_ADMIN')) {
                $content .= 'ADMIN';
            }

            return new Response($content);
        });

        $this->tester->addRoute('admin', '/admin', function () {
            return 'admin';
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
                    'victoria' => ['ROLE_USER', 'victoriasecret'],
                ],
            ])
            ->addAccessRules([['^/admin', 'ROLE_ADMIN']])
            ->addRoleHierarchy(['ROLE_ADMIN' => ['ROLE_USER']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $container = $this->tester->getContainer();

        $this->tester->addRoute('homepage', '/', function () use ($container) {
            $user = $container->get('security.token_storage')->getToken()->getUser();
            $content = is_object($user) ? $user->getUsername() : 'ANONYMOUS';

            return new Response($content);
        });

        $container->set('app.authenticator.token', function () {
            return new TokenAuthenticator();
        });
    }
}
