<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group CreatePaymentMethodCollectionTest
 * Add your own group annotations below this line
 */
class CreatePaymentMethodCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_KEY = 'SprykerCreditCard';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NAME = 'Spryker Credit Card';

    /**
     * @var int
     */
    protected const ID_PAYMNET_PROVIDER = 666;

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
    public function testCreatePaymentMethodCollectionTransactionalReturnsCollectionWithPersistedPaymentMethods(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
                ]))->build(),
            )
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
                ]))->build(),
            )
            ->setIsTransactional(true);

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(2, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(2, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(0, $paymentMethodCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionTransactionalReturnsCollectionWithErrorsAndPaymentMethodsFromRequest(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
        ]);
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
                ]))->build(),
            )
            ->addPaymentMethod($paymentMethodTransfer->setIdPaymentMethod(null))
            ->setIsTransactional(true);

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(2, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionReturnsCollectionWithPaymentMethodHavingCorrectProperties(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = (new PaymentMethodBuilder([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_ACTIVE => false,
        ]))->build();
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentMethod($paymentMethodTransfer);

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertNotNull($paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getIdPaymentMethod());
        $this->assertSame($paymentMethodTransfer->getName(), $paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getName());
        $this->assertSame($paymentMethodTransfer->getPaymentMethodKey(), $paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getPaymentMethodKey());
        $this->assertSame($paymentProviderTransfer->getIdPaymentProvider(), $paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getIdPaymentProvider());
        $this->assertFalse($paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getIsActive());
        $this->assertCount(0, $paymentMethodCollectionResponseTransfer->getPaymentMethods()->offsetGet(0)->getStoreRelation()->getStores());
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionReturnsCollectionWithErrorWhilePaymentMethodAlreadyExists(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
        ]);
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentMethod($paymentMethodTransfer->setIdPaymentMethod(null));

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(0, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method with key "%paymentMethodKey%" already exists.',
            $paymentMethodCollectionResponseTransfer->getErrors()
                ->offsetGet(0)
                ->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionReturnsCollectionWithErrorWhilePaymentMethodProviderIsUnknown(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => static::ID_PAYMNET_PROVIDER,
                ]))->build(),
            );

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(0, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment provider with id "%paymentProviderId%" is unknown.',
            $paymentMethodCollectionResponseTransfer->getErrors()
                ->offsetGet(0)
                ->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionReturnsCollectionWithErrorWhilePaymentMethodKeyIsUsedMoreThanOnce(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
                ]))->build(),
            )
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
                ]))->build(),
            );

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method key "%paymentMethodKey%" used more then once among requested entities.',
            $paymentMethodCollectionResponseTransfer->getErrors()
                ->offsetGet(0)
                ->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionReturnsCollectionWithErrorWhilePaymentMethodNameIsUsedMoreThanOnce(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
                ]))->build(),
            )
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
                ]))->build(),
            );

        // Act
        $paymentMethodCollectionResponseTransfer = $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getPaymentMethods());
        $this->assertCount(1, $paymentMethodCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method name "%paymentMethodName%" used more then once among requested entities.',
            $paymentMethodCollectionResponseTransfer->getErrors()
                ->offsetGet(0)
                ->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionThrowsRequiredTransferPropertyExceptionWhileEmptyRequiredCollection(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())->setIsTransactional(false);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Empty required collection property "' . PaymentMethodCollectionRequestTransfer::PAYMENT_METHODS . '" for transfer Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer.');

        // Act
        $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyIsTransactionalIsMissing(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())->addPaymentMethod(
            (new PaymentMethodBuilder())->build(),
        );

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodCollectionRequestTransfer::IS_TRANSACTIONAL . '" for transfer Generated\Shared\Transfer\PaymentMethodCollectionRequestTransfer.');

        // Act
        $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyPaymentMethodKeyIsMissing(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())->setIsTransactional(false)
            ->addPaymentMethod((new PaymentMethodBuilder([
                PaymentMethodTransfer::PAYMENT_METHOD_KEY => null,
            ]))->build());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodTransfer::PAYMENT_METHOD_KEY . '" for transfer Generated\Shared\Transfer\PaymentMethodTransfer.');

        // Act
        $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyPaymentMethodNameIsMissing(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())->setIsTransactional(false)
            ->addPaymentMethod(
                (new PaymentMethodBuilder([
                    PaymentMethodTransfer::NAME => null,
                ]))->build(),
            );

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodTransfer::NAME . '" for transfer Generated\Shared\Transfer\PaymentMethodTransfer.');

        // Act
        $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethodCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyPaymentProviderIdIsMissing(): void
    {
        // Arrange
        $paymentMethodCollectionRequestTransfer = (new PaymentMethodCollectionRequestTransfer())->setIsTransactional(false)
            ->addPaymentMethod((new PaymentMethodBuilder())->build());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodTransfer::ID_PAYMENT_PROVIDER . '" for transfer Generated\Shared\Transfer\PaymentMethodTransfer.');

        // Act
        $this->paymentFacade->createPaymentMethodCollection($paymentMethodCollectionRequestTransfer);
    }
}
