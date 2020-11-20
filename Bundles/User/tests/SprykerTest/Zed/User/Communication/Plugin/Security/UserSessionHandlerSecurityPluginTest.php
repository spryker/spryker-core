<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\User\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\User\Communication\Plugin\Security\UserSessionHandlerSecurityPlugin;
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
 * @group UserSessionHandlerSecurityPluginTest
 * Add your own group annotations below this line
 */
class UserSessionHandlerSecurityPluginTest extends Unit
{
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

        $this->tester->enableSecurityApplicationPlugin();
    }

    /**
     * @group t
     *
     * @return void
     */
    public function testUserSessionHandlerAddedToContainer(): void
    {
        // Arrange
        $container = $this->tester->getContainer();
        $this->addAuthentication();

        $securityPlugin = new UserSessionHandlerSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $listenerName = sprintf('security.authentication_listener.%s.user_session_handler', static::FIREWALL_NAME);

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Arrange
        $httpKernelBrowser->request('get', '/test');

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
                'anonymous' => true,
            ])
            ->addAccessRules([['^/', 'ROLE_USER']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('test', '/test', function () {
            return new Response();
        });
    }
}
