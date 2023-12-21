<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use ReflectionClass;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfigurator;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\ZedMerchantUserSecurityPlugin;
use SprykerTest\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiCommunicationTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityMerchantPortalGui
 * @group Communication
 * @group Plugin
 * @group Security
 * @group ZedMerchantUserSecurityPluginTest
 * Add your own group annotations below this line
 */
class ZedMerchantUserSecurityPluginTest extends Unit
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
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @var \SprykerTest\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiCommunicationTester
     */
    protected SecurityMerchantPortalGuiCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        if ($this->tester->isSymfonyVersion5() === true) {
            $this->markTestSkipped('Compatible only with `symfony/security-core` package version >= 6. Will be enabled by default once Symfony 5 support is discontinued.');
        }

        $securityPlugin = new ZedMerchantUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
        ]);
        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $this->tester->addRoute('test', '/ignorable', function () {
            return new Response('test-text');
        });

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();

        $csrfToken = $container->get(static::SERVICE_FORM_CSRF_PROVIDER)
            ->getToken('security-merchant-portal-gui');

        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/ignorable');
        $httpKernelBrowser->request(
            'post',
            '/security-merchant-portal-gui/login_check',
            [
                'security-merchant-portal-gui' => [
                    'username' => $userTransfer->getUsername(),
                    'password' => 'foo',
                    '_token' => $csrfToken->getValue(),
                ],
            ],
        );

        // Assert
        /** @var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUser $user */
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
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
        ]);
        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request(
            'post',
            '/security-merchant-portal-gui/login_check',
            [
                'security-merchant-portal-gui' => [
                    'username' => $userTransfer->getUsername(),
                    'password' => 'bar',
                ],
            ],
        );

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return void
     */
    public function testNonActiveUserCanNotLogin(): void
    {
        // Arrange
        $container = $this->tester->getContainer();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::PASSWORD => 'foo',
            UserTransfer::STATUS => 'blocked',
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
        ]);
        $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $this->tester->addRoute('test', '/ignorable', function () {
            return new Response('test-text');
        });

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request('get', '/ignorable');
        $httpKernelBrowser->request(
            'post',
            '/security-merchant-portal-gui/login_check',
            [
                'security-merchant-portal-gui' => [
                    'username' => $userTransfer->getUsername(),
                    'password' => 'foo',
                ],
            ],
        );

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }

    /**
     * @return void
     */
    public function testUserWithInactiveMerchantCanNotLogin(): void
    {
        // Arrange
        $container = $this->tester->getContainer();

        $userTransfer = $this->tester->haveUser([
            UserTransfer::PASSWORD => 'foo',
        ]);
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_WAITING_FOR_APPROVAL,
        ]);
        $merchantUserTransfer = $this->tester->haveMerchantUser($merchantTransfer, $userTransfer);

        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);

        $container->get(static::SERVICE_SESSION)->start();
        $httpKernelBrowser = $this->tester->getHttpKernelBrowser();

        // Act
        $httpKernelBrowser->request(
            'post',
            '/security-merchant-portal-gui/login_check',
            [
                'security-merchant-portal-gui' => [
                    'username' => $userTransfer->getUsername(),
                    'password' => 'foo',
                ],
            ],
        );

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
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
