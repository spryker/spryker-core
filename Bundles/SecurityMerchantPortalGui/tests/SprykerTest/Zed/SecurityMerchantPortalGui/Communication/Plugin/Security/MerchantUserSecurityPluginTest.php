<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin;
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
 * @group MerchantUserSecurityPluginTest
 * Add your own group annotations below this line
 */
class MerchantUserSecurityPluginTest extends Unit
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
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_WAITING_FOR_APPROVAL
     */
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @var \SprykerTest\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiCommunicationTester
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

        $securityPlugin = new MerchantUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

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
            ]
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

        $securityPlugin = new MerchantUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

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
            ]
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

        $securityPlugin = new MerchantUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

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
            ]
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

        $securityPlugin = new MerchantUserSecurityPlugin();
        $securityPlugin->setFactory($this->tester->getFactory());
        $this->tester->addSecurityPlugin($securityPlugin);

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
            ]
        );

        // Assert
        $token = $container->get(static::SERVICE_SECURITY_TOKEN_STORAGE)->getToken();
        $this->assertNull($token);
    }
}
