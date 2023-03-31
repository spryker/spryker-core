<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderConditionsTransfer;
use Generated\Shared\Transfer\PaymentProviderCriteriaTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group GetPaymentProviderCollectionTest
 * Add your own group annotations below this line
 */
class GetPaymentProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER = 'Spryker';

    /**
     * @var \SprykerTest\Zed\Payment\PaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Payment\Business\PaymentFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $paymentFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePaymentProviderTableIsEmpty();

        $this->paymentFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tester->ensurePaymentProviderTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testGetPaymentProviderCollectionReturnsCollectionWithPersistedPaymentProviders(): void
    {
        // Arrange
        $this->tester->havePaymentProvider();
        $this->tester->havePaymentProvider();

        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions(new PaymentProviderConditionsTransfer());

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getPaymentProviderCollection($paymentProviderCriteriaTransfer);

        // Assert
        $this->assertCount(2, $paymentProviderCollectionTransfer->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testGetPaymentProviderCollectionReturnsCollectionWithPaymentProviderByKeys(): void
    {
        // Arrange
        $this->tester->havePaymentProvider();
        $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER,
        ]);

        $paymentProviderConditionsTransfer = (new PaymentProviderConditionsTransfer())->addPaymentProviderKey(static::PAYMENT_PROVIDER);
        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions($paymentProviderConditionsTransfer);

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getPaymentProviderCollection($paymentProviderCriteriaTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionTransfer->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testGetPaymentProviderCollectionReturnsCollectionWithPaymentProviderByNames(): void
    {
        // Arrange
        $this->tester->havePaymentProvider();
        $this->tester->havePaymentProvider([
            PaymentProviderTransfer::NAME => static::PAYMENT_PROVIDER,
        ]);

        $paymentProviderConditionsTransfer = (new PaymentProviderConditionsTransfer())->addName(static::PAYMENT_PROVIDER);
        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions($paymentProviderConditionsTransfer);

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getPaymentProviderCollection($paymentProviderCriteriaTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionTransfer->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testGetPaymentProviderCollectionReturnsCollectionWithNoPaymentProviderByNames(): void
    {
        // Arrange
        $this->tester->havePaymentProvider();

        $paymentProviderConditionsTransfer = (new PaymentProviderConditionsTransfer())->addName(static::PAYMENT_PROVIDER);
        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions($paymentProviderConditionsTransfer);

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getPaymentProviderCollection($paymentProviderCriteriaTransfer);

        // Assert
        $this->assertCount(0, $paymentProviderCollectionTransfer->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testGetPaymentProviderCollectionReturnsCollectionWithPaymentProviderHavingCorrectProperties(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentProviderCriteriaTransfer = (new PaymentProviderCriteriaTransfer())->setPaymentProviderConditions(new PaymentProviderConditionsTransfer());

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getPaymentProviderCollection($paymentProviderCriteriaTransfer);

        // Assert
        $this->assertSame($paymentProviderTransfer->getIdPaymentProvider(), $paymentProviderCollectionTransfer->getPaymentProviders()->offsetGet(0)->getIdPaymentProvider());
        $this->assertSame($paymentProviderTransfer->getName(), $paymentProviderCollectionTransfer->getPaymentProviders()->offsetGet(0)->getName());
        $this->assertSame($paymentProviderTransfer->getPaymentProviderKey(), $paymentProviderCollectionTransfer->getPaymentProviders()->offsetGet(0)->getPaymentProviderKey());
        $this->assertCount(1, $paymentProviderCollectionTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
    }
}
