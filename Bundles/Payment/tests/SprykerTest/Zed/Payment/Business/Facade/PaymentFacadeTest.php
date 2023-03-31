<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Spryker\Client\Payment\PaymentClientInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReader;
use Spryker\Zed\Payment\Business\PaymentBusinessFactory;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\PaymentDependencyProvider;

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
     * @var string
     */
    protected const STORE_REFERENCE = 'dev-DE';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const CHECKOUT_REDIRECT_URL = 'checkout-redirect-url';

    /**
     * @var string
     */
    protected const PAYMENT_AUTHORIZATION_ENDPOINT = 'http://localhost/authorize';

    /**
     * @var string
     */
    protected const PAYMENT_AUTHORIZATION_REDIRECT = 'http://localhost/redirect';

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
        $configMock = $this->createMock(PaymentConfig::class);
        $configMock->method('getPaymentStatemachineMappings')->willReturn([]);
        $paymentBusinessFactory = new PaymentBusinessFactory();
        $paymentBusinessFactory->setConfig($configMock);
        $this->paymentFacade->setFactory($paymentBusinessFactory);

        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
            'AT' => 'dev-AT',
        ]);
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
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'test',
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
        $paymentMethodTransfer->setPaymentMethodKey('test1');

        // Act
        $this->paymentFacade->updatePaymentMethod($paymentMethodTransfer);

        // Assert
        $resultPaymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->findOne();
        $storeRelationExist = SpyPaymentMethodStoreQuery::create()
            ->filterByFkPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->exists();
        $this->assertSame(
            'test1',
            $resultPaymentMethodEntity->getPaymentMethodKey(),
            'Payment method name should match to the expected value',
        );
        $this->assertTrue($storeRelationExist, 'Payment method store relation should exists');
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentMethodsShouldReturnActivePaymentMethod(): void
    {
        // Arrange
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
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'dummyPaymentInvoice',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'dummyPaymentCreditCard',
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
            'Amount of found payment method does not match to the expected value',
        );
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentMethodsShouldCollectPersistentAndInfrastructuralPaymentMethods(): void
    {
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
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'dummyPaymentInvoice',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'dummyPaymentCreditCard',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);
        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'dummyPaymentTest',
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
            2,
            $paymentMethodsTransfer->getMethods(),
            'Amount of found payment method does not match to the expected value',
        );
    }

    /**
     * @return void
     */
    public function testGetAvailablePaymentProvidersForStoreShouldReturnActivePaymentProviderForGivenStore(): void
    {
        // Arrange
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
            'Amount of payment providers does not match the expected value',
        );
        $paymentMethods = $paymentProviderCollectionTransfer->getPaymentProviders()[0]->getPaymentMethods();
        $this->assertCount(
            1,
            $paymentMethods,
            'Amount of payment methods does not match the expected value',
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
    public function testCreatePaymentProvider(): void
    {
        // Arrange
        $paymentProviderTransfer = (new PaymentProviderBuilder())->build();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $paymentMethodTransfer = (new PaymentMethodBuilder())->build();
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
        $paymentProviderTransfer->addPaymentMethod($paymentMethodTransfer);

        // Act
        $paymentProviderResponseTransfer = $this->paymentFacade->createPaymentProvider($paymentProviderTransfer);

        // Assert
        $this->assertTrue($paymentProviderResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($paymentProviderResponseTransfer->getPaymentProvider()->getIdPaymentProvider());
    }

    /**
     * @return void
     */
    public function testCreatePaymentMethod(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $paymentMethodTransfer = (new PaymentMethodBuilder())
            ->seed([PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider()])
            ->build();
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

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->createPaymentMethod($paymentMethodTransfer);

        // Assert
        $this->assertTrue($paymentMethodResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($paymentMethodResponseTransfer->getPaymentMethod()->getIdPaymentMethod());
    }

    /**
     * @return void
     */
    public function testDeactivatePaymentMethod(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
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

        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => rand(),
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->deactivatePaymentMethod($paymentMethodTransfer);

        // Assert
        $this->assertTrue($paymentMethodResponseTransfer->getIsSuccessful());
        $this->assertFalse($paymentMethodResponseTransfer->getPaymentMethod()->getIsActive());
    }

    /**
     * @return void
     */
    public function testActivatePaymentMethod(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
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

        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => rand(),
            PaymentMethodTransfer::STORE_RELATION => $storeRelationTransfer,
        ]);

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->activatePaymentMethod($paymentMethodTransfer);

        // Assert
        $this->assertTrue($paymentMethodResponseTransfer->getIsSuccessful());
        $this->assertTrue($paymentMethodResponseTransfer->getPaymentMethod()->getIsActive());
    }

    /**
     * @return void
     */
    public function testForeignPaymentAuthorizerReceivesCorrectResponseAndUsingItAddsRedirectUrlWithCorrectData(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([static::STORE_NAME => static::STORE_REFERENCE]);

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_HIDDEN => false,
            PaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_AUTHORIZATION_ENDPOINT,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $paymentTransfer = (new PaymentTransfer())->setPaymentSelection(
            sprintf('%s[%s]', PaymentTransfer::FOREIGN_PAYMENTS, $paymentMethodTransfer->getPaymentMethodKey()),
        );

        $quoteTransfer = $this->buildQuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);
        $checkoutResponseTransfer = $this->buildCheckoutResponseTransfer();

        $paymentClientMock = $this->getPaymentClientMock();
        $paymentClientMock->expects($this->once())
            ->method('authorizeForeignPayment')
            ->with($this->callback(function (PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer) {
                return $paymentAuthorizeRequestTransfer->getRequestUrl() === static::PAYMENT_AUTHORIZATION_ENDPOINT;
            }))
            ->willReturn(
                (new PaymentAuthorizeResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setRedirectUrl(static::PAYMENT_AUTHORIZATION_REDIRECT),
            );

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertSame(static::PAYMENT_AUTHORIZATION_REDIRECT, $checkoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testForeignPaymentAuthorizerDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        $initialQuoteTransfer = $this->buildQuoteTransfer();
        $initialQuoteTransfer->setPayment(
            (new PaymentTransfer())->setPaymentSelection($paymentMethodTransfer->getPaymentMethodKey()),
        );
        $initialCheckoutResponseTransfer = $this->buildCheckoutResponseTransfer();

        $quoteTransfer = clone $initialQuoteTransfer;
        $checkoutResponseTransfer = clone $initialCheckoutResponseTransfer;

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertEquals($initialQuoteTransfer->toArray(), $quoteTransfer->toArray());
        $this->assertEquals($initialCheckoutResponseTransfer->toArray(), $checkoutResponseTransfer->toArray());
    }

    /**
     * @return void
     */
    public function testEnablePaymentMethodReturnsSavedPaymentMethodTransferWithCorrectData(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([static::STORE_NAME => static::STORE_REFERENCE]);

        $paymentMethodAddedTransfer = $this->tester->getPaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => 'name-1',
            PaymentMethodAddedTransfer::PROVIDER_NAME => 'provider-name-1',
            PaymentMethodAddedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
        ], [
            MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
        ]);

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()
            ->enableForeignPaymentMethod($paymentMethodAddedTransfer);

        $createdPaymentMethodAddedTransfer = $this->tester->mapPaymentMethodTransferToPaymentMethodAddedTransfer(
            $createdPaymentMethodTransfer,
            new PaymentMethodAddedTransfer(),
        );

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsHidden());

        $this->assertSame($paymentMethodAddedTransfer->getName(), $createdPaymentMethodAddedTransfer->getName());
        $this->assertSame($paymentMethodAddedTransfer->getProviderName(), $createdPaymentMethodAddedTransfer->getProviderName());
        $this->assertSame($paymentMethodAddedTransfer->getPaymentAuthorizationEndpoint(), $createdPaymentMethodAddedTransfer->getPaymentAuthorizationEndpoint());
    }

    /**
     * @return void
     */
    public function testDisableForeignPaymentMethodSetsPaymentMethodIsDeletedFlagToTrueWithCorrectData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->getStoreTransfer([
            StoreTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
        ]);
        $this->tester->setStoreReferenceData([static::STORE_NAME => static::STORE_REFERENCE]);

        $paymentMethodAddedTransfer = $this->tester->getPaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => 'name-2',
            PaymentMethodAddedTransfer::PROVIDER_NAME => 'provider-name-2',
            PaymentMethodAddedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
        ], [
            MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
        ]);

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->enableForeignPaymentMethod($paymentMethodAddedTransfer);

        $paymentMethodDeletedTransfer = $this->tester->mapPaymentMethodTransferToPaymentMethodDeletedTransfer(
            $paymentMethodTransfer,
            (new PaymentMethodDeletedTransfer())
                ->setMessageAttributes($paymentMethodAddedTransfer->getMessageAttributes()),
        );
        $this->tester->getFacade()->disableForeignPaymentMethod($paymentMethodDeletedTransfer);

        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod());
        $updatedPaymentMethodTransfer = $this->tester->findPaymentMethod($filterPaymentMethodTransfer);

        // Assert
        $this->assertSame($paymentMethodTransfer->getIdPaymentMethod(), $updatedPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertTrue($updatedPaymentMethodTransfer->getIsHidden());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Payment\PaymentClientInterface
     */
    protected function getPaymentClientMock(): PaymentClientInterface
    {
        $paymentClient = $this->getMockBuilder(PaymentClientInterface::class)->getMock();
        $this->tester->setDependency(PaymentDependencyProvider::CLIENT_PAYMENT, $paymentClient);

        return $paymentClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function buildCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return (new CheckoutResponseBuilder())
            ->withSaveOrder()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withItem()
            ->withStore([
                'name' => static::STORE_NAME,
            ])
            ->withCustomer()
            ->withTotals()
            ->withCurrency()
            ->withBillingAddress()
            ->build();
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
                ->addMethod(
                    (new PaymentMethodTransfer())->setMethodName('dummyPaymentInvoice')
                        ->setPaymentMethodKey('dummyPaymentInvoice'),
                ),
        );

        $container = new Container();
        /** @var \Spryker\Zed\Payment\Business\PaymentBusinessFactory $paymentBusinessFactoryMock */
        $paymentBusinessFactoryMock = $this->getMockBuilder(PaymentBusinessFactory::class)
            ->onlyMethods(['createPaymentMethodReader', 'getPaymentService'])
            ->getMock();
        $paymentBusinessFactoryMock->method('createPaymentMethodReader')
            ->willReturn($paymentMethodReaderMock);
        $paymentBusinessFactoryMock->method('getPaymentService')
            ->willReturn($container->getLocator()->payment()->service());

        $this->paymentFacade->setFactory($paymentBusinessFactoryMock);
    }
}
