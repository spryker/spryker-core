<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReader;
use Spryker\Zed\Payment\Business\PaymentBusinessFactory;
use Spryker\Zed\Payment\PaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentFacadeTest
 * Add your own group annotations below this line
 */
class PaymentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Payment\PaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Payment\Business\PaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentFacade = $this->tester->getFacade();
        $configMock = $this->createMock(PaymentConfig::class);
        $configMock->method('getPaymentStatemachineMappings')->willReturn([]);
        $factory = new PaymentBusinessFactory();
        $factory->setConfig($configMock);
        $this->paymentFacade->setFactory($factory);
    }

    /**
     * @return void
     */
    public function testFindPaymentMethodByIdShouldFindPaymentMethod(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->findPaymentMethodById($paymentMethodTransfer->getIdPaymentMethod());

        // Assert
        $this->assertTrue($paymentMethodResponseTransfer->getIsSuccessful(), 'Payment method should be found');
        $this->assertNotNull($paymentMethodResponseTransfer->getPaymentMethod(), 'Payment method should not be empty');
    }

    /**
     * @return void
     */
    public function testFindPaymentMethodByIdWithNotExistingIdShouldNotFindPaymentMethod(): void
    {
        // Arrange
        $this->tester->ensurePaymentMethodTableIsEmpty();

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->findPaymentMethodById(1);

        // Assert
        $this->assertFalse($paymentMethodResponseTransfer->getIsSuccessful(), 'Payment method should not be found');
    }

    /**
     * @return void
     */
    public function testUpdatePaymentMethodShouldUpdatePaymentMethodWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::METHOD_NAME => 'test',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_ENTITY => $paymentMethodTransfer->getIdPaymentMethod(),
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();
        $paymentMethodTransfer->setStoreRelation($storeRelationTransfer);
        $paymentMethodTransfer->setMethodName('test1');

        // Act
        $this->paymentFacade->updatePaymentMethod($paymentMethodTransfer);

        // Assert
        $resultPaymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->findOne();
        $storeRelationExist = SpyPaymentMethodStoreQuery::create()
            ->filterByFkPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->exists();
        $this->assertEquals('test1', $resultPaymentMethodEntity->getPaymentMethodKey(), 'Payment method name should match to the expected value');
        $this->assertTrue($storeRelationExist, 'Payment method store relation should exists');
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentMethodsShouldReturnActivePaymentMethod(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'dummyPayment',
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::METHOD_NAME => 'dummyPaymentInvoice',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::METHOD_NAME => 'dummyPaymentCreditCard',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $quoteTransfer = (new QuoteBuilder())->withStore([
            StoreTransfer::NAME => $storeTransfer->getName(),
        ])->build();

        // Act
        $paymentMethodsTransfer = $this->paymentFacade->getAvailableMethods($quoteTransfer);

        // Assert
        $this->assertCount(
            1,
            $paymentMethodsTransfer->getMethods(),
            'Amount of found payment method does not match to the expected value'
        );
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentMethodsShouldCollectPersistentAndInfrastructuralPaymentMethods(): void
    {
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'dummyPayment',
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::METHOD_NAME => 'dummyPaymentInvoice',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::METHOD_NAME => 'dummyPaymentCreditCard',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::METHOD_NAME => 'dummyPaymentTest',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => null,
        ]);
        $configMock = $this->createMock(PaymentConfig::class);
        $configMock->method('getPaymentStatemachineMappings')->willReturn([
            'dummyPaymentInvoice' => 'statemachine1',
            'dummyPaymentCreditCard' => 'statemachine2',
            'dummyPaymentTest' => 'statemachine3',
            'not_in_db' => 'statemachine4',
        ]);

        $factory = new PaymentBusinessFactory();
        $factory->setConfig($configMock);
        $this->paymentFacade->setFactory($factory);
        $quoteTransfer = (new QuoteBuilder())->withStore([
            StoreTransfer::NAME => $storeTransfer->getName(),
        ])->build();

        // Act
        $paymentMethodsTransfer = $this->paymentFacade->getAvailableMethods($quoteTransfer);

        $this->assertCount(
            3,
            $paymentMethodsTransfer->getMethods(),
            'Amount of found payment method does not match to the expected value'
        );
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentProvidersForStoreShouldReturnActivePaymentProviderForGivenStore(): void
    {
        // Arrange
        $this->tester->ensurePaymentProviderTableIsEmpty();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();
        $paymentProviderOne = $this->tester->havePaymentProvider();
        $paymentProviderTwo = $this->tester->havePaymentProvider();
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderOne->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
            PaymentMethodTransfer::NAME => 'test1',
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderOne->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
            PaymentMethodTransfer::NAME => 'test2',
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderOne->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::STORE_RELATION => null,
            PaymentMethodTransfer::NAME => 'test3',
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTwo->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::NAME => 'test4',
        ]);

        // Act
        $paymentProviderCollectionTransfer = $this->paymentFacade->getAvailablePaymentProvidersForStore('DE');

        // Assert
        $this->assertCount(
            1,
            $paymentProviderCollectionTransfer->getPaymentProviders(),
            'Amount of payment providers does not match the expected value'
        );
        $paymentMethods = $paymentProviderCollectionTransfer->getPaymentProviders()[0]->getPaymentMethods();
        $this->assertCount(
            1,
            $paymentMethods,
            'Amount of payment methods does not match the expected value'
        );
    }

    /**
     * @return void
     */
    public function testIsQuotePaymentMethodValidShouldReturnTrueIfPaymentExists(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withPayment(['payment_selection' => 'dummyPaymentInvoice'])
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();
        $this->mockPaymentMethodReader();

        // Act
        $isPaymentMethodExists = $this->paymentFacade->isQuotePaymentMethodValid($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isPaymentMethodExists);
    }

    /**
     * @return void
     */
    public function testIsQuotePaymentMethodValidShouldReturnFalseIfPaymentNotExists(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withPayment(['payment_selection' => 'NotExists'])
            ->build();
        $checkoutResponseTransfer = (new CheckoutResponseBuilder())->build();
        $this->mockPaymentMethodReader();

        // Act
        $isPaymentMethodExists = $this->paymentFacade->isQuotePaymentMethodValid($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isPaymentMethodExists);
    }

    /**
     * @return void
     */
    protected function mockPaymentMethodReader(): void
    {
        $paymentMethodReaderMock = $this->getMockBuilder(PaymentMethodReader::class)
            ->onlyMethods(['getAvailableMethods'])
            ->disableOriginalConstructor()
            ->getMock();
        $paymentMethodReaderMock->method('getAvailableMethods')->willReturn(
            (new PaymentMethodsTransfer())
                ->addMethod((new PaymentMethodTransfer())->setMethodName('dummyPaymentInvoice'))
        );

        /** @var \Spryker\Zed\Payment\Business\PaymentBusinessFactory $paymentBusinessFactoryMock */
        $paymentBusinessFactoryMock = $this->getMockBuilder(PaymentBusinessFactory::class)
            ->onlyMethods(['createPaymentMethodReader'])
            ->getMock();
        $paymentBusinessFactoryMock->method('createPaymentMethodReader')
            ->willReturn($paymentMethodReaderMock);

        $this->paymentFacade->setFactory($paymentBusinessFactoryMock);
    }
}
