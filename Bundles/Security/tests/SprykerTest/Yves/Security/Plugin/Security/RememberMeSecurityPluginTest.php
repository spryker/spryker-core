<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\EventDispatcher\EventDispatcherFactory;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Yves\Router\Plugin\EventDispatcher\RouterListenerEventDispatcherPlugin;
use Spryker\Yves\Security\Configuration\SecurityConfiguration;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Security
 * @group Plugin
 * @group Security
 * @group RememberMeSecurityPluginTest
 * Add your own group annotations below this line
 */
class RememberMeSecurityPluginTest extends Unit
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
     * @var bool
     */
    public $interactiveLoginTriggered = false;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->interactiveLoginTriggered = false;
    }

    /**
     * @return void
     */
    public function testRememberMeAuthentication(): void
    {
        $this->addAuthentication();
        $this->addEventDispatcher();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $httpKernelBrowser->request('get', '/');
        $this->assertFalse($this->interactiveLoginTriggered, 'The interactive login has been triggered');

        $httpKernelBrowser->request('post', '/login_check', ['_username' => 'user', '_password' => 'foo', '_remember_me' => 'true']);
        $httpKernelBrowser->followRedirect();
        $this->assertEquals('AUTHENTICATED_FULLY', $httpKernelBrowser->getResponse()->getContent());
        $this->assertTrue($this->interactiveLoginTriggered, 'The interactive login has not been triggered yet');
        $this->assertNotNull($httpKernelBrowser->getCookiejar()->get('REMEMBERME'), 'The REMEMBERME cookie is not set');

        $httpKernelBrowser->getCookiejar()->expire('MOCKSESSID');
        $httpKernelBrowser->request('get', '/');
        $this->assertEquals('AUTHENTICATED_REMEMBERED', $httpKernelBrowser->getResponse()->getContent());
        $this->assertTrue($this->interactiveLoginTriggered, 'The interactive login has not been triggered yet');

        $httpKernelBrowser->request('get', '/logout');
        $httpKernelBrowser->followRedirect();
        $this->assertNull($httpKernelBrowser->getCookiejar()->get('REMEMBERME'), 'The REMEMBERME cookie has not been removed yet');
    }

    /**
     * @return void
     */
    protected function addAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('http-auth', [
                'pattern' => '^.*$',
                'form' => true,
                'remember_me' => [],
                'logout' => true,
                'users' => [
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
            ]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/', function () {
            $authorizationChecker = $this->getAuthorizationChecker();
            if ($authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                return new Response('AUTHENTICATED_FULLY');
            }
            if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                return new Response('AUTHENTICATED_REMEMBERED');
            }

            return new Response('AUTHENTICATED_ANONYMOUSLY');
        });
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        $container = $this->tester->getContainer();

        return $container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }

    /**
     * @return void
     */
    protected function onInteractiveLogin(): void
    {
        $this->interactiveLoginTriggered = true;
    }

    /**
     * @return void
     */
    protected function addEventDispatcher(): void
    {
        $eventDispatcherApplicationPlugin = new EventDispatcherApplicationPlugin();
        $eventDispatcherApplicationPlugin->setFactory($this->getEventDispatcherFactoryMock());
        $this->tester->addApplicationPlugin($eventDispatcherApplicationPlugin);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\EventDispatcher\EventDispatcherFactory
     */
    protected function getEventDispatcherFactoryMock()
    {
        $eventDispatcherFactoryMock = $this->getMockBuilder(EventDispatcherFactory::class)
            ->setMethods(['getEventDispatcherPlugins'])
            ->getMock();

        $sessionEventDispatcherPlugin = new class implements EventDispatcherPluginInterface {
            /**
             * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
             */
            public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
            {
                $eventDispatcher->addListener(KernelEvents::REQUEST, function (GetResponseEvent $event) {
                    $session = new Session(new MockFileSessionStorage());
                    $event->getRequest()->setSession($session);
                    $cookies = $event->getRequest()->cookies;

                    if ($cookies->has($session->getName())) {
                        $session->setId($cookies->get($session->getName()));
                    } else {
                        $session->migrate(false);
                    }
                }, 192);

                $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) {
                    $session = $event->getRequest()->getSession();
                    if ($session && $session->isStarted()) {
                        $session->save();

                        $params = session_get_cookie_params();

                        $event->getResponse()->headers->setCookie(new Cookie(
                            $session->getName(),
                            $session->getId(),
                            $params['lifetime'] === 0 ? 0 : time() + $params['lifetime'],
                            $params['path'],
                            $params['domain'],
                            $params['secure'],
                            $params['httponly'],
                            false,
                            null
                        ));
                    }
                }, -128);

                return $eventDispatcher;
            }
        };

        $testClass = $this;
        $testEventDispatcherPlugin = new class ($testClass) implements EventDispatcherPluginInterface {

            /**
             * @var \SprykerTest\Yves\Security\Plugin\Security\RememberMeSecurityPluginTest
             */
            protected $testClass;

            /**
             * @param \SprykerTest\Yves\Security\Plugin\Security\RememberMeSecurityPluginTest $testClass
             */
            public function __construct(RememberMeSecurityPluginTest $testClass)
            {
                $this->testClass = $testClass;
            }

            /**
             * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
             */
            public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
            {
                $eventDispatcher->addListener(SecurityEvents::INTERACTIVE_LOGIN, function () {
                    $this->testClass->interactiveLoginTriggered = true;
                });

                return $eventDispatcher;
            }
        };

        $eventDispatcherFactoryMock->method('getEventDispatcherPlugins')->willReturn([
            new RouterListenerEventDispatcherPlugin(),
            $sessionEventDispatcherPlugin,
            $testEventDispatcherPlugin,
        ]);

        return $eventDispatcherFactoryMock;
    }
}
