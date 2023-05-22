<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Payment\PaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group ExpandPaymentWithPaymentSelectionTest
 * Add your own group annotations below this line
 */
class ExpandPaymentWithPaymentSelectionTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PAYMENT_SELECTION = 'TEST_PAYMENT_SELECTION';

    /**
     * @var \SprykerTest\Zed\Payment\PaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Payment\Business\PaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->paymentFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testExpandPaymentWithPaymentSelectionWorksForPaymentTransferWithNoPaymentSelectionSet(): void
    {
        // Arrange
        $store = $this->tester->haveStore();
        $paymentProvider = $this->tester->havePaymentProvider();
        $paymentMethod = $this->preparePaymentMethod($paymentProvider);

        $paymentTransfer = $this->preparePaymentTransfer($paymentMethod, $paymentProvider);

        // Act
        $paymentTransfer = $this->paymentFacade->expandPaymentWithPaymentSelection($paymentTransfer, $store);

        // Assert
        $this->assertNotEmpty($paymentTransfer->getPaymentSelection());
    }

    /**
     * @return void
     */
    public function testExpandPaymentWithPaymentSelectionDoesNothingForPaymentTransferWithPaymentSelectionSet(): void
    {
        // Arrange
        $store = $this->tester->haveStore();
        $paymentProvider = $this->tester->havePaymentProvider();
        $paymentMethod = $this->preparePaymentMethod($paymentProvider);

        $paymentTransfer = $this->preparePaymentTransfer($paymentMethod, $paymentProvider, [
            PaymentTransfer::PAYMENT_SELECTION => static::TEST_PAYMENT_SELECTION,
        ]);

        // Act
        $paymentTransfer = $this->paymentFacade->expandPaymentWithPaymentSelection($paymentTransfer, $store);

        // Assert
        $this->assertEquals(static::TEST_PAYMENT_SELECTION, $paymentTransfer->getPaymentSelection());
    }

    /**
     * @return void
     */
    public function testExpandPaymentWithPaymentSelectionWorksForPaymentTransferWithForeignPaymentMethodAndNoPaymentSelectionSet(): void
    {
        // Arrange
        $store = $this->tester->haveStore();
        $paymentProvider = $this->tester->havePaymentProvider();
        $paymentMethod = $this->preparePaymentMethod($paymentProvider, [
            PaymentMethodTransfer::IS_FOREIGN => true,
        ]);

        $paymentTransfer = $this->preparePaymentTransfer($paymentMethod, $paymentProvider);

        // Act
        $paymentTransfer = $this->paymentFacade->expandPaymentWithPaymentSelection($paymentTransfer, $store);

        // Assert
        $this->assertStringContainsString(PaymentConfig::PAYMENT_FOREIGN_PROVIDER, $paymentTransfer->getPaymentSelection());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProvider
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function preparePaymentMethod(PaymentProviderTransfer $paymentProvider, array $seed = []): PaymentMethodTransfer
    {
        return $this->tester->havePaymentMethod(array_merge($seed, [
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProvider->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores(1),
        ]));
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethod
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProvider
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function preparePaymentTransfer(
        PaymentMethodTransfer $paymentMethod,
        PaymentProviderTransfer $paymentProvider,
        array $seed = []
    ): PaymentTransfer {
        return (new PaymentBuilder(array_merge($seed, [
            PaymentTransfer::PAYMENT_PROVIDER => $paymentProvider->getPaymentProviderKey(),
            PaymentTransfer::PAYMENT_METHOD => $paymentMethod->getName(),
        ])))->build();
    }
}
