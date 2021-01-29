<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\Security\SecurityDependencyProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Security
 * @group Business
 * @group Facade
 * @group Facade
 * @group SecurityFacadeTest
 * Add your own group annotations below this line
 */
class SecurityFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Security\SecurityBusinessTester
     */
    protected $tester;

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_AUTHORIZATION_CHECKER
     */
    protected const SERVICE_SECURITY_AUTHORIZATION_CHECKER = 'security.authorization_checker';

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
    public function testIsUserLoggedInReturnsFalseForAuthenticatedUser(): void
    {
        // Arrange
        $this->addAuthentication();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/homepage');
        $this->mockDependencies();
        $isUserLoggedIn = $this->tester->isUserLoggedIn();

        // Assert
        $this->assertFalse($isUserLoggedIn);
    }

    /**
     * @return void
     */
    public function testIsUserLoggedInReturnsTrueForAuthenticatedUser(): void
    {
        // Arrange
        $this->addAuthentication();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('post', '/homepage/login_check', ['_username' => 'user', '_password' => 'foo']);
        $this->mockDependencies();
        $isUserLoggedIn = $this->tester->isUserLoggedIn();

        // Assert
        $this->assertTrue($isUserLoggedIn);
    }

    /**
     * @return void
     */
    protected function addAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('http-auth', [
                'pattern' => '^/homepage',
                'form' => [
                    'require_previous_session' => false,
                    'check_path' => '/homepage/login_check',
                ],
                'anonymous' => true,
                'users' => [
                    'user' => ['ROLE_USER', '$2y$15$lzUNsTegNXvZW3qtfucV0erYBcEqWVeyOmjolB7R1uodsAVJ95vvu'],
                ],
            ]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('homepage', '/homepage', function () {
            return new Response();
        });
    }

    /**
     * @return void
     */
    protected function mockDependencies(): void
    {
        $container = $this->tester->getContainer();
        $this->tester->setDependency(
            SecurityDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER,
            $container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER)
        );
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        $container = $this->tester->getContainer();

        return $container->get(static::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }
}
