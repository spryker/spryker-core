<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantApp\Communication\Plugin\KernelApp;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\MerchantApp\Communication\Plugin\KernelApp\MerchantAppRequestExpanderPlugin;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantApp\MerchantAppDependencyProvider;
use Spryker\Zed\MerchantUser\Business\Exception\CurrentMerchantUserNotFoundException;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use SprykerTest\Zed\MerchantApp\MerchantAppCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantApp
 * @group Communication
 * @group Plugin
 * @group KernelApp
 * @group AcpRequestExpanderPluginTest
 * Add your own group annotations below this line
 */
class AcpRequestExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantApp\MerchantAppCommunicationTester
     */
    protected MerchantAppCommunicationTester $tester;

    /**
     * @return void
     */
    public function testMerchantReferenceGetsAddedWhenMerchantUserIsLoggedIn(): void
    {
        // Arrange
        $merchantReference = Uuid::uuid4()->toString();
        $merchantUserTransfer = new MerchantUserTransfer();
        $merchantTransfer = new MerchantTransfer();
        $merchantTransfer->setMerchantReference($merchantReference);
        $merchantUserTransfer->setMerchant($merchantTransfer);

        $merchantUserFacadeMock = Stub::makeEmpty(MerchantUserFacadeInterface::class, [
            'getCurrentMerchantUser' => $merchantUserTransfer,
        ]);

        $this->tester->setDependency(MerchantAppDependencyProvider::FACADE_MERCHANT_USER, new MerchantAppToMerchantUserFacadeBridge($merchantUserFacadeMock));

        $acpRequestExpanderPlugin = new MerchantAppRequestExpanderPlugin();

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod('GET')
            ->setUri('http://localhost');

        // Act
        $acpHttpRequestTransfer = $acpRequestExpanderPlugin->expandRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertArrayHasKey('x-merchant-reference', $acpHttpRequestTransfer->getHeaders());
        $this->assertSame($merchantReference, $acpHttpRequestTransfer->getHeaders()['x-merchant-reference']);
    }

    /**
     * @return void
     */
    public function testMerchantReferenceIsNotAddedWhenMerchantUserIsNotLoggedIn(): void
    {
        // Arrange
        $merchantUserFacadeMock = Stub::makeEmpty(MerchantUserFacadeInterface::class, [
            'getCurrentMerchantUser' => function (): void {
                throw new CurrentMerchantUserNotFoundException();
            },
        ]);

        $this->tester->setDependency(MerchantAppDependencyProvider::FACADE_MERCHANT_USER, new MerchantAppToMerchantUserFacadeBridge($merchantUserFacadeMock));

        $acpRequestExpanderPlugin = new MerchantAppRequestExpanderPlugin();

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod('GET')
            ->setUri('http://localhost');

        // Act
        $acpHttpRequestTransfer = $acpRequestExpanderPlugin->expandRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertArrayNotHasKey('x-merchant-reference', $acpHttpRequestTransfer->getHeaders());
    }

    /**
     * @return void
     */
    public function testMerchantReferenceIsNotAddedWhenMerchantUserIsLoggedInButMerchantNotInTransfer(): void
    {
        // Arrange
        $merchantUserFacadeMock = Stub::makeEmpty(MerchantUserFacadeInterface::class, [
            'getCurrentMerchantUser' => new MerchantUserTransfer(),
        ]);

        $this->tester->setDependency(MerchantAppDependencyProvider::FACADE_MERCHANT_USER, new MerchantAppToMerchantUserFacadeBridge($merchantUserFacadeMock));

        $acpRequestExpanderPlugin = new MerchantAppRequestExpanderPlugin();

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod('GET')
            ->setUri('http://localhost');

        // Act
        $acpHttpRequestTransfer = $acpRequestExpanderPlugin->expandRequest($acpHttpRequestTransfer);

        // Assert
        $this->assertArrayNotHasKey('x-merchant-reference', $acpHttpRequestTransfer->getHeaders());
    }
}
