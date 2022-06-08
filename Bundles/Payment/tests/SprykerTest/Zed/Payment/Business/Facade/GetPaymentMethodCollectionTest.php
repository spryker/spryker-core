<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group GetPaymentMethodCollectionTest
 * Add your own group annotations below this line
 */
class GetPaymentMethodCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER = 'Spryker';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_KEY = 'SprykerCreditCard';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NAME = 'Spryker Credit Card';

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

        $this->paymentFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithPersistedPaymentMethods(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions(new PaymentMethodConditionsTransfer());

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(2, $paymentMethodCollectionTransfer->getPaymentMethods());
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithPaymentMethodByNames(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentMethodConditionsTransfer = (new PaymentMethodConditionsTransfer())
            ->addName(static::PAYMENT_METHOD_NAME);
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $paymentMethodCollectionTransfer->getPaymentMethods());
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithPaymentMethodByPaymentMethodKey(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentMethodConditionsTransfer = (new PaymentMethodConditionsTransfer())
            ->addPaymentMethodKey(static::PAYMENT_METHOD_KEY);
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $paymentMethodCollectionTransfer->getPaymentMethods());
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithPaymentMethodByNamesAndPaymentMethodKey(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentMethodConditionsTransfer = (new PaymentMethodConditionsTransfer())
            ->addName(static::PAYMENT_METHOD_NAME)
            ->addPaymentMethodKey(static::PAYMENT_METHOD_KEY);
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(1, $paymentMethodCollectionTransfer->getPaymentMethods());
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithNoPaymentMethodByNamesAndPaymentMethodKey(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentMethodConditionsTransfer = (new PaymentMethodConditionsTransfer())
            ->addName(static::PAYMENT_METHOD_NAME)
            ->addPaymentMethodKey(static::PAYMENT_METHOD_KEY);
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertCount(0, $paymentMethodCollectionTransfer->getPaymentMethods());
    }

    /**
     * @return void
     */
    public function testGetPaymentMethodCollectionReturnsCollectionWithPaymentMethodHavingCorrectProperties(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $paymentMethodCriteriaTransfer = (new PaymentMethodCriteriaTransfer())->setPaymentMethodConditions(new PaymentMethodConditionsTransfer());

        // Act
        $paymentMethodCollectionTransfer = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        // Assert
        $this->assertSame($paymentMethodTransfer->getIdPaymentMethod(), $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getIdPaymentMethod());
        $this->assertSame($paymentMethodTransfer->getName(), $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getName());
        $this->assertSame($paymentMethodTransfer->getPaymentMethodKey(), $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getPaymentMethodKey());
        $this->assertSame($paymentMethodTransfer->getIdPaymentProvider(), $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getIdPaymentProvider());
        $this->assertSame($paymentMethodTransfer->getIsActive(), $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getIsActive());
        $this->assertCount(0, $paymentMethodCollectionTransfer->getPaymentMethods()->offsetGet(0)->getStoreRelation()->getStores());
    }
}
