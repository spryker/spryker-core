<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionRequestTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group CreatePaymentProviderCollectionTest
 * Add your own group annotations below this line
 */
class CreatePaymentProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER_NAME = 'Spryker';

    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER_KEY = 'spryker';

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
    public function testCreatePaymentProviderCollectionReturnsCollectionWithPersistedPaymentProvidersHavingAssociatedPaymentMethodsAndNoErrors(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY . 1,
                PaymentProviderTransfer::NAME => static::PAYMENT_PROVIDER_NAME . 1,
            ]))
                ->withPaymentMethod([
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME . 1,
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY . 1,
                ])
                ->build())
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY . 2,
                PaymentProviderTransfer::NAME => static::PAYMENT_PROVIDER_NAME . 2,
            ]))
                ->withPaymentMethod([
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME . 2,
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY . 2,
                ])
                ->build())
            ->setIsTransactional(false);

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(1)->getPaymentMethods());
        $this->assertSame(2, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertSame(2, $this->tester->getNumberOfPersistentPaymentMethods());
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionTransactionalReturnsCollectionWithPersistedPaymentProvidersHavingAssociatedPaymentMethodsAndNoErrors(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->addPaymentProvider((new PaymentProviderBuilder(['idPaymentProvider' => null]))
                ->withPaymentMethod()
                ->build())
            ->addPaymentProvider((new PaymentProviderBuilder(['idPaymentProvider' => null]))
                ->withPaymentMethod()
                ->build())
            ->setIsTransactional(true);

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(1)->getPaymentMethods());
        $this->assertSame(2, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertSame(2, $this->tester->getNumberOfPersistentPaymentMethods());
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionTransactionalReturnsCollectionWithErrorsAndPaymentProvidersFromRequest(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY,
        ]);
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->addPaymentProvider(
                (new PaymentProviderBuilder([
                PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
                ]))->build(),
            )->addPaymentProvider(
                (new PaymentProviderBuilder([
                    PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
                    PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY,
                ]))->build(),
            )->setIsTransactional(true);

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithPaymentProviderHavingCorrectProperties(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();

        $paymentProviderTransfer = (new PaymentProviderBuilder())->withPaymentMethod()
            ->build();

        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider($paymentProviderTransfer);

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertNotNull($paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getIdPaymentProvider());
        $this->assertSame($paymentProviderTransfer->getName(), $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getName());
        $this->assertSame($paymentProviderTransfer->getPaymentProviderKey(), $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentProviderKey());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
        $this->assertSame(
            $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getIdPaymentProvider(),
            $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods()->offsetGet(0)->getIdPaymentProvider(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentProviderAlreadyExists(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider(
                (new PaymentProviderBuilder([
                    PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
                    PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderTransfer->getPaymentProviderKeyOrFail(),
                 ]))->build(),
            );

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment provider with key "%paymentProviderKey%" already exists.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentProviderMethodAlreadyExists(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProviderOrFail(),
        ]);
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::ID_PAYMENT_PROVIDER => null,
            ]))->withPaymentMethod([
                PaymentMethodTransfer::PAYMENT_METHOD_KEY => $paymentMethodTransfer->getPaymentMethodKeyOrFail(),
            ])->build());

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method with key "%paymentMethodKey%" already exists.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyPaymentProvidersIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())->setIsTransactional(false);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Empty required collection property "' . PaymentProviderCollectionRequestTransfer::PAYMENT_PROVIDERS . '" for transfer ' . PaymentProviderCollectionRequestTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPropertyIsTransactionalIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = (new PaymentProviderBuilder())->build();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->addPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentProviderCollectionRequestTransfer::IS_TRANSACTIONAL . '" for transfer ' . PaymentProviderCollectionRequestTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPaymentProviderNameIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = (new PaymentProviderBuilder([
            PaymentProviderTransfer::NAME => null,
        ]))->build();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentProviderTransfer::NAME . '" for transfer ' . PaymentProviderTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPaymentProviderKeyIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = (new PaymentProviderBuilder([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => null,
        ]))->build();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentProviderTransfer::PAYMENT_PROVIDER_KEY . '" for transfer ' . PaymentProviderTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPaymentMethodNameIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = (new PaymentProviderBuilder())
            ->withAnotherPaymentMethod()
            ->withAnotherPaymentMethod([
                PaymentMethodTransfer::NAME => null,
            ])->build();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodTransfer::NAME . '" for transfer ' . PaymentMethodTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionThrowsRequiredTransferPropertyExceptionWhileRequiredPaymentMethodKeyIsMissing(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = (new PaymentProviderBuilder())
            ->withAnotherPaymentMethod()
            ->withAnotherPaymentMethod([
                PaymentMethodTransfer::PAYMENT_METHOD_KEY => null,
            ])->build();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage('Missing required property "' . PaymentMethodTransfer::PAYMENT_METHOD_KEY . '" for transfer ' . PaymentMethodTransfer::class . '.');

        // Act
        $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentProviderNameIsUsedMoreThanOnce(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::NAME => static::PAYMENT_PROVIDER_NAME,
            ]))->build())
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::NAME => static::PAYMENT_PROVIDER_NAME,
            ]))->build());

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment provider name "%paymentProviderName%" used more then once among requested entities.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentProviderKeyIsUsedMoreThanOnce(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY,
            ]))->build())
            ->addPaymentProvider((new PaymentProviderBuilder([
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => static::PAYMENT_PROVIDER_KEY,
            ]))->build());

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment provider key "%paymentProviderKey%" used more then once among requested entities.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentMethodNameIsUsedMoreThanOnce(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider((new PaymentProviderBuilder())
                ->withAnotherPaymentMethod([
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
                ])
                ->withAnotherPaymentMethod([
                    PaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
                ])->build());

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method name "%paymentMethodName%" used more then once among requested entities.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePaymentProviderCollectionReturnsCollectionWithErrorWhilePaymentMethodKeyIsUsedMoreThanOnce(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderCollectionRequestTransfer = (new PaymentProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addPaymentProvider((new PaymentProviderBuilder())
                ->withAnotherPaymentMethod([
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
                ])
                ->withAnotherPaymentMethod([
                    PaymentMethodTransfer::PAYMENT_METHOD_KEY => static::PAYMENT_METHOD_KEY,
                ])->build());

        // Act
        $paymentProviderCollectionResponseTransfer = $this->paymentFacade->createPaymentProviderCollection($paymentProviderCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getPaymentProviders()->offsetGet(0)->getPaymentMethods());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentProviders());
        $this->assertSame(1, $this->tester->getNumberOfPersistentPaymentMethods());
        $this->assertCount(1, $paymentProviderCollectionResponseTransfer->getErrors());
        $this->assertSame(
            'Payment method key "%paymentMethodKey%" used more then once among requested entities.',
            $paymentProviderCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }
}
