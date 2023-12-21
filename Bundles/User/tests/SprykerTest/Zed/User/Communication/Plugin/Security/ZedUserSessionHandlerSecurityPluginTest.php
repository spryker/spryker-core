<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\User\Communication\Plugin\Security\ZedUserSessionHandlerSecurityPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group User
 * @group Communication
 * @group Plugin
 * @group Security
 * @group ZedUserSessionHandlerSecurityPluginTest
 * Add your own group annotations below this line
 */
class ZedUserSessionHandlerSecurityPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FIREWALL_NAME = 'test-firewall';

    /**
     * @var \SprykerTest\Zed\User\UserCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        if ($this->tester->isSymfonyVersion5() === true) {
            $this->markTestSkipped('Compatible only with `symfony/security-core` package version >= 6. Will be enabled by default once Symfony 5 support is discontinued.');
        }
    }

    /**
     * @return void
     */
    public function testUserSessionHandlerAddedToContainer(): void
    {
        // Arrange
        $securityPlugin = new ZedUserSessionHandlerSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

        $this->addAuthentication();

        $listenerName = sprintf('security.authentication_listener.%s.user_session_handler', static::FIREWALL_NAME);
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Arrange
        $container = $this->tester->getContainer();

        // Assert
        $this->assertTrue($container->has($listenerName));
    }

    /**
     * @return void
     */
    protected function addAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall(static::FIREWALL_NAME, [
                'pattern' => '^/test',
                'user_session_handler' => true,
            ])
            ->addAccessRules([['^/', 'ROLE_USER']]);

        $this->tester->mockZedSecurityPlugin($securityConfiguration);
        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();

        $this->tester->addRoute('test', '/test', function () {
            return new Response();
        });
    }
}
