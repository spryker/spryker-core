<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Form\AgentMerchantLoginForm;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Plugin\Security\ZedAgentMerchantUserSecurityPlugin;
use Spryker\Zed\MerchantAgent\Communication\Plugin\User\MerchantAgentUserQueryCriteriaExpanderPlugin;
use Spryker\Zed\User\UserDependencyProvider;
use SprykerTest\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiCommunicationTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentSecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group ZedAgentMerchantUserSecurityPluginTest
 * Add your own group annotations below this line
 */
class ZedAgentMerchantUserSecurityPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TOKEN_STORAGE = 'security.token_storage';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin::SERVICE_SESSION
     *
     * @var string
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'TEST_PASSWORD';

    /**
     * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::PATH_LOGIN_CHECK
     *
     * @var string
     */
    protected const PATH_LOGIN_CHECK = '/agent-security-merchant-portal-gui/login_check';

    /**
     * @var string
     */
    protected const ROUTE_TEST = 'test';

    /**
     * @var string
     */
    protected const PATH_TEST = '/test';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_ACTIVE
     *
     * @var string
     */
    protected const COL_STATUS_ACTIVE = 'active';

    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS_BLOCKED
     *
     * @var string
     */
    protected const COL_STATUS_BLOCKED = 'blocked';

    /**
     * @var \SprykerTest\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiCommunicationTester
     */
    protected AgentSecurityMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->tester->setDependency(UserDependencyProvider::PLUGINS_USER_QUERY_CRITERIA_EXPANDER, [new MerchantAgentUserQueryCriteriaExpanderPlugin()]);

        $zedAgentMerchantUserSecurityPlugin = new ZedAgentMerchantUserSecurityPlugin();
        $zedAgentMerchantUserSecurityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($zedAgentMerchantUserSecurityPlugin);
        $this->tester->addSecurityPlugin($this->createMerchantUserSecurityPlugin());

        $this->tester->mockSecurityDependencies();
        $this->tester->enableSecurityApplicationPlugin();

        $this->tester->addRoute(static::ROUTE_TEST, static::PATH_TEST, function () {
            return new Response('test-text');
        });

        $this->tester->resetSecurityConfiguration();
    }

    /**
     * @return void
     */
    public function testUserCanLogin(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser([
            UserTransfer::PASSWORD => static::TEST_PASSWORD,
            UserTransfer::STATUS => static::COL_STATUS_ACTIVE,
            UserTransfer::IS_MERCHANT_AGENT => true,
        ]);

        $container = $this->tester->getContainer();

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();

        $csrfToken = $container->get(static::SERVICE_FORM_CSRF_PROVIDER)
            ->getToken(AgentMerchantLoginForm::FORM_NAME);

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', static::PATH_TEST);
        $httpKernelBrowser->request(
            'post',
            static::PATH_LOGIN_CHECK,
            [
                AgentMerchantLoginForm::FORM_NAME => [
                    AgentMerchantLoginForm::FIELD_USERNAME => $userTransfer->getUsername(),
                    AgentMerchantLoginForm::FIELD_PASSWORD => static::TEST_PASSWORD,
                    '_token' => $csrfToken->getValue(),
                ],
            ],
        );

        // Assert
        /** @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUser $agentMerchantUser */
        $agentMerchantUser = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken()->getUser();
        $this->assertSame($userTransfer->getUsername(), $agentMerchantUser->getUsername());
    }

    /**
     * @dataProvider getUserCanNotLoginDataProvider
     *
     * @param array<string, mixed> $userData
     * @param string $password
     *
     * @return void
     */
    public function testUserCanNotLogin(array $userData, string $password): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser($userData);

        $container = $this->tester->getContainer();

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        $csrfToken = $container->get(static::SERVICE_FORM_CSRF_PROVIDER)
            ->getToken(AgentMerchantLoginForm::FORM_NAME);

        // Act
        $httpKernelBrowser->request('get', static::PATH_TEST);
        $httpKernelBrowser->request(
            'post',
            static::PATH_LOGIN_CHECK,
            [
                AgentMerchantLoginForm::FORM_NAME => [
                    AgentMerchantLoginForm::FIELD_USERNAME => $userTransfer->getUsername(),
                    AgentMerchantLoginForm::FIELD_PASSWORD => $password,
                ],
                '_token' => $csrfToken->getValue(),
            ],
        );

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return array<string, array<array<string, mixed>|string>>
     */
    protected function getUserCanNotLoginDataProvider(): array
    {
        return [
            'When credentials are invalid.' => [
                [
                    UserTransfer::PASSWORD => static::TEST_PASSWORD,
                    UserTransfer::STATUS => static::COL_STATUS_ACTIVE,
                    UserTransfer::IS_MERCHANT_AGENT => true,
                ],
                'wrong_password',
            ],
            'When user is not active.' => [
                [
                    UserTransfer::PASSWORD => static::TEST_PASSWORD,
                    UserTransfer::STATUS => static::COL_STATUS_BLOCKED,
                    UserTransfer::IS_MERCHANT_AGENT => true,
                ],
                static::TEST_PASSWORD,
            ],
            'When user is not merchant agent.' => [
                [
                    UserTransfer::PASSWORD => static::TEST_PASSWORD,
                    UserTransfer::STATUS => static::COL_STATUS_ACTIVE,
                    UserTransfer::IS_MERCHANT_AGENT => false,
                ],
                static::TEST_PASSWORD,
            ],
        ];
    }

    /**
     * @return \Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface
     */
    protected function createMerchantUserSecurityPlugin(): SecurityPluginInterface
    {
        return new class implements SecurityPluginInterface
        {
            /**
             * @uses \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig::MERCHANT_USER_SECURITY_FIREWALL_NAME
             *
             * @var string
             */
            protected const MERCHANT_USER_SECURITY_FIREWALL_NAME = 'MerchantUser';

            /**
             * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
             */
            public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
            {
                return $securityBuilder->addFirewall(
                    static::MERCHANT_USER_SECURITY_FIREWALL_NAME,
                    [],
                );
            }
        };
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->resetSecurityConfiguration();
    }
}
