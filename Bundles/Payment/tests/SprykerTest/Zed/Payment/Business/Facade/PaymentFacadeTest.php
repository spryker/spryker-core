<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use DateTimeZone;
use Generated\Shared\DataBuilder\CheckoutResponseBuilder;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Ramsey\Uuid\Uuid;
use Spryker\Client\Payment\PaymentClientInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReader;
use Spryker\Zed\Payment\Business\PaymentBusinessFactory;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\PaymentDependencyProvider;
use SprykerTest\Zed\Payment\PaymentBusinessTester;

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
    protected PaymentBusinessTester $tester;

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

        $paymentClientMock = $this->getMockBuilder(PaymentClientInterface::class)->getMock();

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

        $this->tester->setDependency(PaymentDependencyProvider::CLIENT_PAYMENT, $paymentClientMock);

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertSame(static::PAYMENT_AUTHORIZATION_REDIRECT, $checkoutResponseTransfer->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testForeignPaymentAuthorizerForwardsAdditionPaymentDataToThePaymentServiceProviderApp(): void
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

        $additionalPaymentData = [
            'internalId' => Uuid::uuid4()->toString(),
            'externalId' => Uuid::uuid4()->toString(),
        ];

        $paymentTransfer->setAdditionalPaymentData($additionalPaymentData);

        $quoteTransfer = $this->buildQuoteTransfer();
        $quoteTransfer->setPayment($paymentTransfer);
        $checkoutResponseTransfer = $this->buildCheckoutResponseTransfer();

        $paymentClientMock = $this->getMockBuilder(PaymentClientInterface::class)->getMock();

        $forwardedAdditionPaymentData = [];

        $paymentClientMock->expects($this->once())
            ->method('authorizeForeignPayment')
            ->with($this->callback(function (PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer) use (&$forwardedAdditionPaymentData) {
                // This is what would be sent, we want to compare later.
                $forwardedAdditionPaymentData = $paymentAuthorizeRequestTransfer->getPostData()['orderData'][PaymentTransfer::ADDITIONAL_PAYMENT_DATA];

                return $paymentAuthorizeRequestTransfer->getRequestUrl() === static::PAYMENT_AUTHORIZATION_ENDPOINT;
            }))
            ->willReturn(
                (new PaymentAuthorizeResponseTransfer())
                    ->setIsSuccessful(true)
                    ->setRedirectUrl(static::PAYMENT_AUTHORIZATION_REDIRECT),
            );

        $this->tester->setDependency(PaymentDependencyProvider::CLIENT_PAYMENT, $paymentClientMock);

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertSame($forwardedAdditionPaymentData, $additionalPaymentData);
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
    public function testInitForeignPaymentForCheckoutProcessReturnsRedirectToRelativePaymentPageFromConfig(): void
    {
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

        $paymentClientMock = $this->getMockBuilder(PaymentClientInterface::class)->getMock();

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

        $this->tester->setDependency(PaymentDependencyProvider::CLIENT_PAYMENT, $paymentClientMock);

        $this->tester->mockConfigMethod('getStoreFrontPaymentPage', '/my-custom-payment-page');

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertSame(
            '/my-custom-payment-page?' . http_build_query(['url' => base64_encode(static::PAYMENT_AUTHORIZATION_REDIRECT)]),
            $checkoutResponseTransfer->getRedirectUrl(),
        );
    }

    /**
     * @return void
     */
    public function testInitForeignPaymentForCheckoutProcessReturnsRedirectToAbsolutePaymentPageOnAnotherDomainFromConfig(): void
    {
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

        $paymentClientMock = $this->getMockBuilder(PaymentClientInterface::class)->getMock();

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

        $this->tester->setDependency(PaymentDependencyProvider::CLIENT_PAYMENT, $paymentClientMock);
        $this->tester->mockConfigMethod('getStoreFrontPaymentPage', 'https://my-custom-domain.com/payment?some=param');

        // Act
        $this->tester->getFacade()->initForeignPaymentForCheckoutProcess($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsExternalRedirect());
        $this->assertSame(
            'https://my-custom-domain.com/payment?some=param&' . http_build_query(['url' => base64_encode(static::PAYMENT_AUTHORIZATION_REDIRECT)]),
            $checkoutResponseTransfer->getRedirectUrl(),
        );
    }

    /**
     * @return void
     */
    public function testEnablePaymentMethodReturnsSavedPaymentMethodTransferWithCorrectData(): void
    {
        // Arrange
        $this->tester->setStoreReferenceData([static::STORE_NAME => static::STORE_REFERENCE]);

        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer([
            AddPaymentMethodTransfer::NAME => 'name-1',
            AddPaymentMethodTransfer::PROVIDER_NAME => 'provider-name-1',
            AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
        ], [
            MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
        ]);

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()
            ->enableForeignPaymentMethod($addPaymentMethodTransfer);

        $createdAddPaymentMethodTransfer = $this->tester->mapPaymentMethodTransferToAddPaymentMethodTransfer(
            $createdPaymentMethodTransfer,
            new AddPaymentMethodTransfer(),
        );

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsHidden());

        $this->assertSame($addPaymentMethodTransfer->getName(), $createdAddPaymentMethodTransfer->getName());
        $this->assertSame($addPaymentMethodTransfer->getProviderName(), $createdAddPaymentMethodTransfer->getProviderName());
        $this->assertSame($addPaymentMethodTransfer->getPaymentAuthorizationEndpoint(), $createdAddPaymentMethodTransfer->getPaymentAuthorizationEndpoint());
    }

    /**
     * @return void
     */
    public function testAddPaymentMethodReturnsSavedPaymentMethodTransferWithCorrectData(): void
    {
        // Arrange
        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer(
            [
                AddPaymentMethodTransfer::NAME => 'name-1',
                AddPaymentMethodTransfer::PROVIDER_NAME => 'provider-name-1',
                AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
            ],
            [],
        );

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()
            ->addPaymentMethod($addPaymentMethodTransfer);

        $createdAddPaymentMethodTransfer = $this->tester->mapPaymentMethodTransferToAddPaymentMethodTransfer(
            $createdPaymentMethodTransfer,
            new AddPaymentMethodTransfer(),
        );

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsHidden());

        $this->assertSame($addPaymentMethodTransfer->getName(), $createdAddPaymentMethodTransfer->getName());
        $this->assertSame($addPaymentMethodTransfer->getProviderName(), $createdAddPaymentMethodTransfer->getProviderName());
        $this->assertSame($addPaymentMethodTransfer->getPaymentAuthorizationEndpoint(), $createdAddPaymentMethodTransfer->getPaymentAuthorizationEndpoint());
    }

    /**
     * Reflecting an Update of a PaymentMethod.
     *
     * @return void
     */
    public function testGivenThePaymentMethodAlreadyExistsAndIsActiveWhenTheAddPaymentMethodMessageIsHandledThenThePaymentMethodIsUpdatedAndIsStillActive(): void
    {
        // Arrange
        $paymentMethodName = 'MethodName' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'ProviderKey' . Uuid::uuid4()->toString();

        $paymentProviderTransfer = $this->tester->havePaymentProvider([PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_PROVIDER => $paymentProviderTransfer,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_FOREIGN => true,
        ]);

        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer(
            [
                AddPaymentMethodTransfer::NAME => $paymentMethodName,
                AddPaymentMethodTransfer::PROVIDER_NAME => $paymentProviderKey,
                AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
            ],
        );

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()->addPaymentMethod($addPaymentMethodTransfer);

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsHidden(), 'Expected that the payment method is visible but it is hidden');
        $this->assertTrue($createdPaymentMethodTransfer->getIsActive(), 'Expected that the payment method is active but it is inactive');
    }

    /**
     * Reflecting an Update of a PaymentMethod.
     *
     * @return void
     */
    public function testGivenThePaymentMethodAlreadyExistsAndIsInactiveWhenTheAddPaymentMethodMessageIsHandledThenThePaymentMethodIsUpdatedAndIsStillInctive(): void
    {
        // Arrange
        $paymentMethodName = 'MethodName' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'ProviderKey' . Uuid::uuid4()->toString();

        $paymentProviderTransfer = $this->tester->havePaymentProvider([PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey]);

        $this->tester->havePaymentMethod([
            PaymentMethodTransfer::IS_ACTIVE => false,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_PROVIDER => $paymentProviderTransfer,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::IS_FOREIGN => true,
        ]);

        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer(
            [
                AddPaymentMethodTransfer::NAME => $paymentMethodName,
                AddPaymentMethodTransfer::PROVIDER_NAME => $paymentProviderKey,
                AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
            ],
        );

        // Act
        $createdPaymentMethodTransfer = $this->tester->getFacade()->addPaymentMethod($addPaymentMethodTransfer);

        // Assert
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertNotNull($createdPaymentMethodTransfer->getIdPaymentProvider());
        $this->assertFalse($createdPaymentMethodTransfer->getIsHidden(), 'Expected that the payment method is visible but it is hidden');
        $this->assertFalse($createdPaymentMethodTransfer->getIsActive(), 'Expected that the payment method is inactive but it is active');
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

        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer([
            AddPaymentMethodTransfer::NAME => 'name-2',
            AddPaymentMethodTransfer::PROVIDER_NAME => 'provider-name-2',
            AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
        ], [
            MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
        ]);

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->enableForeignPaymentMethod($addPaymentMethodTransfer);

        $deletePaymentMethodTransfer = $this->tester->mapPaymentMethodTransferToDeletePaymentMethodTransfer(
            $paymentMethodTransfer,
            (new DeletePaymentMethodTransfer())
                ->setMessageAttributes($addPaymentMethodTransfer->getMessageAttributes()),
        );
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodTransfer);

        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod());
        $updatedPaymentMethodTransfer = $this->tester->findPaymentMethod($filterPaymentMethodTransfer);

        // Assert
        $this->assertSame($paymentMethodTransfer->getIdPaymentMethod(), $updatedPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertTrue($updatedPaymentMethodTransfer->getIsHidden());
    }

    /**
     * @return void
     */
    public function testDeletePaymentMethodSetsPaymentMethodIsDeletedFlagToTrueWithCorrectData(): void
    {
        // Arrange
        $addPaymentMethodTransfer = $this->tester->haveAddPaymentMethodTransfer(
            [
                AddPaymentMethodTransfer::NAME => 'name-2',
                AddPaymentMethodTransfer::PROVIDER_NAME => 'provider-name-2',
                AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => 'redirect-url',
            ],
            [],
        );

        // Act
        $paymentMethodTransfer = $this->tester->getFacade()
            ->addPaymentMethod($addPaymentMethodTransfer);

        $deletePaymentMethodTransfer = $this->tester->mapPaymentMethodTransferToDeletePaymentMethodTransfer(
            $paymentMethodTransfer,
            (new DeletePaymentMethodTransfer())
                ->setMessageAttributes($addPaymentMethodTransfer->getMessageAttributes()),
        );
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodTransfer);

        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod());
        $updatedPaymentMethodTransfer = $this->tester->findPaymentMethod($filterPaymentMethodTransfer);

        // Assert
        $this->assertSame($paymentMethodTransfer->getIdPaymentMethod(), $updatedPaymentMethodTransfer->getIdPaymentMethod());
        $this->assertTrue($updatedPaymentMethodTransfer->getIsHidden());
    }

    /**
     * When the `disableForeignPaymentMethod()` method is called and the Payment Method doesn't exist yet
     * (disable message arrived before add message), it must be created and stored with `is_hidden=true` (soft deletion)
     * so the add message can be handled without adding it after it got removed (Payment method gets updated and stays as soft deleted).
     *
     * @return void
     */
    public function testDisablePaymentMethodMessageCreatesPaymentMethodAndMarkItAsDeletedWhenThePaymentMethodDoesNotExistsBeforeTheDeleteMessageArrives(): void
    {
        // Arrange
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithoutTimestamp();

        // Act
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasCreatedWithSoftDeletion(
            $deletePaymentMethodMessage,
        );
    }

    /**
     * When the `deletePaymentMethod()` method is called and the Payment Method doesn't exist yet
     * (disable message arrived before add message), it must be created and stored with `is_hidden=true` (soft deletion)
     * so the add message can be handled without adding it after it got removed (Payment method gets updated and stays as soft deleted).
     *
     * @return void
     */
    public function testDeletePaymentMethodMessageCreatesPaymentMethodAndMarkItAsDeletedWhenThePaymentMethodDoesNotExistsBeforeTheDeleteMessageArrives(): void
    {
        // Arrange
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithoutTimestamp();

        // Act
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasCreatedWithSoftDeletion(
            $deletePaymentMethodMessage,
            false,
        );
    }

    /**
     * If the `AddPaymentMethodTransfer` comes from a message when `enableForeignPaymentMethod()` is called
     * it must compare its timestamp with the last message timestamp stored on the existing payment method record.
     * If the last message timestamp stored on the existing payment method record is newer the method must not do any change.
     *
     * @return void
     */
    public function testAddPaymentMethodMessageShouldNotChangeDeletedStateOfPaymentMethodWhenDeletePaymentMethodMessageWasSentAfterAddPaymentMethodMessage(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->enableForeignPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodDidNotChange($disabledPaymentMethod);
    }

    /**
     * If the `AddPaymentMethodTransfer` comes from a message when `enableForeignPaymentMethod()` is called
     * it must compare its timestamp with the last message timestamp stored on the existing payment method record.
     * If the last message timestamp stored on the existing payment method record is newer the method must not do any change.
     *
     * @return void
     */
    public function testAddPaymentMethodMessageShouldNotChangeStateOfPaymentMethodWhenDeletePaymentMethodMessageWasSentAfterAddPaymentMethodMessage(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->addPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodDidNotChange($disabledPaymentMethod);
    }

    /**
     * If the last message timestamp is null when `enableForeignPaymentMethod()` is called it should always
     * proceed with the change and update the timestamp.
     *
     * @return void
     */
    public function testEnableForeignPaymentMethodShouldChangeDeletedStateOfPaymentMethodWhenPaymentMethodsLastMessageTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithoutTimestampOnDatabase($paymentProviderTransfer);

        // Act
        $this->tester->getFacade()->enableForeignPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasEnabledAndTimestampChanged(
            $disabledPaymentMethod,
            $addPaymentMethodMessage,
        );
    }

    /**
     * If the last message timestamp is null when `addPaymentMethod()` is called it should always
     * proceed with the change and update the timestamp.
     *
     * @return void
     */
    public function testAddPaymentMethodShouldChangeDeletedStateOfPaymentMethodWhenPaymentMethodsLastMessageTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithoutTimestampOnDatabase(
            $paymentProviderTransfer,
            false,
        );

        // Act
        $this->tester->getFacade()->addPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasEnabledAndTimestampChanged(
            $disabledPaymentMethod,
            $addPaymentMethodMessage,
        );
    }

    /**
     * If `AddPaymentMethodTransfer` doesn't come from a message it likely will not have a timestamp and this can not
     * avoid the process to keep on running and the change must happen, and the timestamp must be updated.
     *
     * @return void
     */
    public function testEnabledForeignPaymentMethodShouldChangeDeletedStateOfPaymentMethodWhenAddPaymentMethodTransferDoNotComeFromAMessageAndItsTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithoutTimestamp($paymentProviderTransfer);
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->enableForeignPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasEnabledAndTimestampWasUpdated(
            $disabledPaymentMethod,
        );
    }

    /**
     * If `AddPaymentMethodTransfer` doesn't come from a message it likely will not have a timestamp and this can not
     * avoid the process to keep on running and the change must happen, and the timestamp must be updated.
     *
     * @return void
     */
    public function testEnabledForeignPaymentMethodShouldModifyStateOfPaymentMethodWhenAddPaymentMethodTransferDoNotComeFromAMessageAndItsTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithoutTimestamp($paymentProviderTransfer);
        $disabledPaymentMethod = $this->tester->createDisabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
            false,
        );

        // Act
        $this->tester->getFacade()->addPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertDisabledPaymentMethodWasEnabledAndTimestampWasUpdated(
            $disabledPaymentMethod,
        );
    }

    /**
     * If the `DeletePaymentMethodTransfer` comes from a message when `disableForeignPaymentMethod()` is called
     * it must compare its timestamp with the last message timestamp stored on the existing payment method record.
     * If the last message timestamp stored on the existing payment method record is newer the method must not do any change.
     *
     * @return void
     */
    public function testDisableForeignPaymentMethodShouldNotChangeEnabledStateOfPaymentMethodIfLastMessageTimestampIsOlderThanDeletePaymentMethodTransferTimestamp(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodDidNotChange($enabledPaymentMethod);
    }

    /**
     * If the `DeletePaymentMethodTransfer` comes from a message when `deletePaymentMethod()` is called
     * it must compare its timestamp with the last message timestamp stored on the existing payment method record.
     * If the last message timestamp stored on the existing payment method record is newer the method must not do any change.
     *
     * @return void
     */
    public function testDeletePaymentMethodShouldNotChangeEnabledStateOfPaymentMethodIfLastMessageTimestampIsOlderThanDeletePaymentMethodTransferTimestamp(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
            false,
        );

        // Act
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodDidNotChange($enabledPaymentMethod);
    }

    /**
     * If the last message timestamp is null when `disableForeignPaymentMethod()` is called it should always
     * proceed with the change and update the timestamp.
     *
     * @return void
     */
    public function testDisableForeignPaymentMethodShouldChangeEnabledStateOfPaymentMethodWhenPaymentMethodsLastMessageTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithoutTimestampOnDatabase($paymentProviderTransfer);

        // Act
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodWasDisabledAndTimestampChanged(
            $enabledPaymentMethod,
            $deletePaymentMethodMessage,
        );
    }

    /**
     * If the last message timestamp is null when `deletePaymentMethod()` is called it should always
     * proceed with the change and update the timestamp.
     *
     * @return void
     */
    public function testDeletePaymentMethodShouldChangeEnabledStateOfPaymentMethodWhenPaymentMethodsLastMessageTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithoutTimestampOnDatabase(
            $paymentProviderTransfer,
            false,
        );

        // Act
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodWasDisabledAndTimestampChanged(
            $enabledPaymentMethod,
            $deletePaymentMethodMessage,
        );
    }

    /**
     * If `DeletePaymentMethodTransfer` doesn't come from a message it likely will not have a timestamp and this can not
     * avoid the process to keep up running and the change must happen, and the timestamp must be updated.
     *
     * @return void
     */
    public function testDisableForeignPaymentMethodShouldChangeEnabledStateOfPaymentMethodWhenDeletePaymentMethodTransferDoNotComeFromAMessageAndItsTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithoutTimestamp($paymentProviderTransfer);
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodWasDisabledAndTimestampWasUpdated(
            $enabledPaymentMethod,
            $deletePaymentMethodMessage,
        );
    }

    /**
     * If `DeletePaymentMethodTransfer` doesn't come from a message it likely will not have a timestamp and this can not
     * avoid the process to keep up running and the change must happen, and the timestamp must be updated.
     *
     * @return void
     */
    public function testDeletePaymentMethodShouldChangeEnabledStateOfPaymentMethodWhenDeletePaymentMethodTransferDoNotComeFromAMessageAndItsTimestampIsNull(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithoutTimestamp($paymentProviderTransfer);
        $enabledPaymentMethod = $this->tester->createEnabledPaymentMethodWithTimestampOnDatabase(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
            false,
        );

        // Act
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertEnabledPaymentMethodWasDisabledAndTimestampWasUpdated(
            $enabledPaymentMethod,
            $deletePaymentMethodMessage,
        );
    }

    /**
     * Tests if a new Payment Method will be created and the Payment Method that already
     * exists will not be modified when PaymentMethodUpdater::disableForeignPaymentMethod() is called.
     *
     * This bug was reported on https://spryker.atlassian.net/browse/PBC-1674.
     *
     * @return void
     */
    public function testEnableForeignPaymentMethodMustChangeTheRightPaymentMethodWhenThereIsMoreThanOneMethodStored(): void
    {
        // Arrange
        $existentPaymentMethod = $this->tester->createEnabledPaymentMethodWithoutTimestampOnDatabase(
            $this->tester->havePaymentProvider(),
        );

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->disableForeignPaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertRightPaymentMethodWasUpdated(
            $existentPaymentMethod,
            $deletePaymentMethodMessage->getProviderName(),
        );
    }

    /**
     * Tests if a new Payment Method will be created and the Payment Method that already
     * exists will not be modified when PaymentMethodUpdater::deletePaymentMethod() is called.
     *
     * This bug was reported on https://spryker.atlassian.net/browse/PBC-1674.
     *
     * @return void
     */
    public function testDeletePaymentMethodMustChangeTheRightPaymentMethodWhenThereIsMoreThanOneMethodStored(): void
    {
        // Arrange
        $existentPaymentMethod = $this->tester->createEnabledPaymentMethodWithoutTimestampOnDatabase(
            $this->tester->havePaymentProvider(),
            false,
        );

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $deletePaymentMethodMessage = $this->tester->haveDeletePaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->deletePaymentMethod($deletePaymentMethodMessage);

        // Assert
        $this->tester->assertRightPaymentMethodWasUpdated(
            $existentPaymentMethod,
            $deletePaymentMethodMessage->getProviderName(),
            false,
        );
    }

    /**
     * Tests if a new Payment Method will be created and the Payment Method that already
     * exists will not be modified when PaymentMethodUpdater::enableForeignPaymentMethod() is called.
     *
     * This bug was reported on https://spryker.atlassian.net/browse/PBC-1674.
     *
     * @return void
     */
    public function testDisableForeignPaymentMethodMustChangeTheRightPaymentMethodWhenThereIsMoreThanOneMethodStored(): void
    {
        // Arrange
        $existentPaymentMethod = $this->tester->createDisabledPaymentMethodWithoutTimestampOnDatabase(
            $this->tester->havePaymentProvider(),
        );

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->enableForeignPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertRightPaymentMethodWasUpdated(
            $existentPaymentMethod,
            $addPaymentMethodMessage->getProviderName(),
        );
    }

    /**
     * Tests if a new Payment Method will be created and the Payment Method that already
     * exists will not be modified when PaymentMethodUpdater::addPaymentMethod() is called.
     *
     * This bug was reported on https://spryker.atlassian.net/browse/PBC-1674.
     *
     * @return void
     */
    public function testDisableForeignPaymentMethodMustModifyTheRightPaymentMethodWhenThereIsMoreThanOneMethodStored(): void
    {
        // Arrange
        $existentPaymentMethod = $this->tester->createDisabledPaymentMethodWithoutTimestampOnDatabase(
            $this->tester->havePaymentProvider(),
            false,
        );

        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $addPaymentMethodMessage = $this->tester->haveAddPaymentMethodTransferWithTimestamp(
            $paymentProviderTransfer,
            $this->generateNowTimestamp(),
        );

        // Act
        $this->tester->getFacade()->addPaymentMethod($addPaymentMethodMessage);

        // Assert
        $this->tester->assertRightPaymentMethodWasUpdated(
            $existentPaymentMethod,
            $addPaymentMethodMessage->getProviderName(),
            false,
        );
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
            ->withShippingAddress()
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

    /**
     * @return string
     */
    protected function generateNowTimestamp(): string
    {
        return (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.u');
    }

    /**
     * @return void
     */
    public function testGeneratePaymentMethodKeyReturnsPaymentMethodKeyForGivenProviderAndMethod(): void
    {
        // Arrange, Act, Assert
        $this->assertSame('foo-bar-baz', $this->paymentFacade->generatePaymentMethodKey('foo', 'bar baz'));
    }
}
