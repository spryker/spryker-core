<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecuritySystemUser\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\HttpRequestTransfer;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\Security\SystemUserSecurityPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\SessionRedis\SystemUserSessionRedisLifeTimeCalculatorPlugin;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecuritySystemUser
 * @group Communication
 * @group Plugin
 * @group Security
 * @group SystemUserSecurityPluginTest
 * Add your own group annotations below this line
 */
class SystemUserSecurityPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin::SERVICE_SESSION
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @uses \Spryker\Zed\Security\Communication\Plugin\Application\SecurityApplicationPlugin::SERVICE_SECURITY_TOKEN_STORAGE
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @var \SprykerTest\Zed\SecuritySystemUser\SecuritySystemUserCommunicationTester
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
    public function testSystemUserCanAccessGatewayControllers(): void
    {
        // Arrange
        $securityPlugin = new SystemUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->addAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/test/gateway/', [], [], $this->createRequestHeaders($securityPlugin));

        // Assert
        $this->assertSame('test-text', $httpKernelBrowser->getResponse()->getContent());
    }

    /**
     * @return void
     */
    public function testSystemUserCanNotAccessGatewayControllersWithInvalidCredentials(): void
    {
        // Arrange
        $securityPlugin = new SystemUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);
        $this->addAuthentication();

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();
        $invalidCredentials = [
            'HTTP_' . strtoupper(SecuritySystemUserConfig::AUTH_TOKEN) => 'invalid_token',
        ];

        // Act
        $httpKernelBrowser->request('get', '/test/gateway/', [], [], $invalidCredentials);

        // Assert
        $this->assertSame(403, $httpKernelBrowser->getResponse()->getStatusCode());
    }

    /**
     * @return void
     */
    public function testSystemUserSessionRedisLifeTimeCalculatorPluginReturnsTrueForValidHeader(): void
    {
        // Arrange
        $systemUserSessionRedisLifeTimeCalculatorPlugin = new SystemUserSessionRedisLifeTimeCalculatorPlugin();
        $httpRequestTransfer = (new HttpRequestTransfer())->addHeader(strtolower(SecuritySystemUserConfig::AUTH_TOKEN), 'value');

        // Act
        $isApplicable = $systemUserSessionRedisLifeTimeCalculatorPlugin->isApplicable($httpRequestTransfer);

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testSystemUserSessionRedisLifeTimeCalculatorPluginReturnsFalseForEmptyHeader(): void
    {
        // Arrange
        $systemUserSessionRedisLifeTimeCalculatorPlugin = new SystemUserSessionRedisLifeTimeCalculatorPlugin();

        // Act
        $isApplicable = $systemUserSessionRedisLifeTimeCalculatorPlugin->isApplicable(new HttpRequestTransfer());

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    protected function addAuthentication(): void
    {
        $securityConfiguration = new SecurityConfiguration();
        $securityConfiguration
            ->addFirewall('default', [
                'pattern' => '^/',
                'form' => true,
            ])
            ->addAccessRules([['^/', 'ROLE_USER']]);

        $this->tester->mockSecurityPlugin($securityConfiguration);

        $this->tester->addRoute('test', '/test/gateway/', function () {
            return new Response('test-text');
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractPlugin $securityPlugin
     *
     * @return array
     */
    protected function createRequestHeaders(AbstractPlugin $securityPlugin): array
    {
        $usersCredentials = $securityPlugin->getConfig()->getUsersCredentials();
        $userCredentials = reset($usersCredentials);
        $token = $this->tester->getLocator()->utilText()->service()->generateToken($userCredentials['token']);

        return [
            'HTTP_' . strtoupper(SecuritySystemUserConfig::AUTH_TOKEN) => $token,
        ];
    }
}
