<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityGui\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use ReflectionClass;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfigurator;
use Spryker\Zed\SecurityGui\Communication\Plugin\Security\ZedUserSecurityPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group ZedUserSecurityPluginTest
 *
 * Add your own group annotations below this line
 */
class ZedUserSecurityPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin::SERVICE_SESSION
     *
     * @var string
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var \SprykerTest\Zed\SecurityGui\SecurityGuiCommunicationTester
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

        $this->tester->addRoute('test', '/ignorable', function () {
            return new Response('test-text');
        });

        $securityPlugin = new ZedUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getCommunicationFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();
    }

    /**
     * @return void
     */
    public function testUserCanLogin(): void
    {
        // Arrange
        $container = $this->tester->getContainer();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::PASSWORD => 'foo',
        ]);

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/ignorable');
        $httpKernelBrowser->request('post', '/login_check', ['auth' => ['username' => $userTransfer->getUsername(), 'password' => 'foo']]);

        // Assert
        /** @var \Spryker\Zed\SecurityGui\Communication\Security\User $user */
        $user = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken()->getUser();
        $this->assertSame($userTransfer->getUsername(), $user->getUsername());
    }

    /**
     * @return void
     */
    public function testUserWithInvalidCredentialsCanNotLogin(): void
    {
        // Arrange
        $container = $this->tester->getContainer();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::PASSWORD => 'foo',
        ]);

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('post', '/login_check', ['auth' => ['username' => $userTransfer->getUsername(), 'password' => 'bar']]);

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return void
     */
    public function testIgnorablePathsAreAccessible(): void
    {
        // Arrange
        $container = $this->tester->getContainer();
        $container->get(static::SERVICE_SESSION)->start();

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/ignorable');

        // Assert
        $this->assertSame('test-text', $httpKernelBrowser->getResponse()->getContent());
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
