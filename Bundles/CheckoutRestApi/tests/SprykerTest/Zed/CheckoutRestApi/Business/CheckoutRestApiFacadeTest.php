<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerResponseBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Shared\CheckoutExtension\CheckoutExtensionContextsInterface;
use Spryker\Shared\Kernel\StrategyResolver;
use Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory;
use Spryker\Zed\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeBridge;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\Quote\Business\QuoteFacade;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CheckoutRestApi
 * @group Business
 * @group Facade
 * @group CheckoutRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CheckoutRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CheckoutRestApi\CheckoutRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @return void
     */
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransfer(): void
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getLocator()->checkoutRestApi()->facade();
        $checkoutRestApiFacade->setFactory($this->getMockCheckoutRestApiFactory());
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $restCheckoutDataResponseTransfer = $checkoutRestApiFacade->getCheckoutData($restCheckoutRequestAttributesTransfer);

        $this->assertNotEmpty($restCheckoutDataResponseTransfer);
        $this->assertTrue($restCheckoutDataResponseTransfer->getIsSuccess());
        $this->assertInstanceOf(RestCheckoutDataResponseTransfer::class, $restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods());
        $this->assertInstanceOf(PaymentProviderCollectionTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentProviders());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses()->getAddresses());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods()->getMethods());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentProviders()->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransferForGuest(): void
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFacade->setFactory($this->getMockCheckoutRestApiFactoryForGuest());
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransferForGuest();

        $restCheckoutDataResponseTransfer = $checkoutRestApiFacade->getCheckoutData($restCheckoutRequestAttributesTransfer);

        $this->assertNotEmpty($restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(RestCheckoutDataResponseTransfer::class, $restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods());
        $this->assertInstanceOf(PaymentProviderCollectionTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentProviders());
        $this->assertCount(0, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses()->getAddresses());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods()->getMethods());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentProviders()->getPaymentProviders());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForCustomer(): void
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFacade->setFactory($this->getMockCheckoutRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForGuest(): void
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFacade->setFactory($this->getMockCheckoutRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransferForGuest();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillFailOnItemOutOfStock(): void
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFacade->setFactory($this->getMockCheckoutRestApiFactoryWithFailingValidation());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertNotTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @dataProvider placeOrderExecutesCheckoutDataValidatorPluginStackWhenItIsSingleDimensional
     *
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     *
     * @return void
     */
    public function testPlaceOrderExecutesCheckoutDataValidatorPluginStackWhenItIsSingleDimensional(
        ?QuoteProcessFlowTransfer $quoteProcessFlowTransfer
    ): void {
        // Arrange
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFactoryMock = $this->getMockCheckoutRestApiFactory($quoteProcessFlowTransfer);
        $checkoutRestApiFactoryMock->method('createCheckoutDataValidatorPluginStrategyResolver')->willReturn(
            new StrategyResolver(
                [
                    CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT => fn () => [$this->getCheckoutDataValidatorPluginMock()],
                ],
                CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT,
            ),
        );
        $checkoutRestApiFacade->setFactory($checkoutRestApiFactoryMock);
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        // Act
        $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);
    }

    /**
     * @dataProvider placeOrderExecutesCheckoutDataValidatorPluginStackWhenItIsMultiDimensional
     *
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     * @param string $pluginStackToCall
     * @param string $pluginStackToIgnore
     *
     * @return void
     */
    public function testPlaceOrderExecutesCheckoutDataValidatorPluginStackWhenItIsMultiDimensional(
        ?QuoteProcessFlowTransfer $quoteProcessFlowTransfer,
        string $pluginStackToCall,
        string $pluginStackToIgnore
    ): void {
        // Arrange
        $checkoutRestApiFacade = $this->tester->getFacade();
        $checkoutRestApiFactoryMock = $this->getMockCheckoutRestApiFactory($quoteProcessFlowTransfer);
        $checkoutRestApiFactoryMock->method('createCheckoutDataValidatorPluginStrategyResolver')->willReturn(
            new StrategyResolver(
                [
                    $pluginStackToCall => fn () => [$this->getCheckoutDataValidatorPluginMock()],
                    $pluginStackToIgnore => fn () => [$this->getNeverCalledCheckoutDataValidatorPluginMock()],
                ],
                CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT,
            ),
        );
        $checkoutRestApiFacade->setFactory($checkoutRestApiFactoryMock);
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        // Act
        $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function initMockCheckoutRestApiFactory(): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory = $this->createPartialMock(
            CheckoutRestApiBusinessFactory::class,
            [
                'getShipmentFacade',
                'getPaymentFacade',
                'getCustomerFacade',
                'getCartFacade',
                'getCheckoutFacade',
                'getQuoteFacade',
                'getCartsRestApiFacade',
                'getQuoteMapperPlugins',
                'createCheckoutDataValidatorPluginStrategyResolver',
                'getReadCheckoutDataValidatorPlugins',
                'getCalculationFacade',
                'getConfig',
                'getCheckoutDataExpanderPlugins',
            ],
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockQuoteFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockQuoteMapperPlugins($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCalculationFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockConfig($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactory(?QuoteProcessFlowTransfer $quoteProcessFlowTransfer = null): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory->setContainer(new Container());

        $mockCheckoutRestApiFactory = $this->addStrategyResolverVanilla($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacade($mockCheckoutRestApiFactory, $quoteProcessFlowTransfer);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryWithFailingValidation(): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacadeWithFailingValidation($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryForGuest(): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addStrategyResolverVanilla($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacadeForGuest($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacadeForGuest($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory
     */
    protected function addStrategyResolverVanilla(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $strategyResolver = new StrategyResolver(
            [
                CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT => fn () => [],
                SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT => fn () => [],
            ],
            CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT,
        );

        $mockCheckoutRestApiFactory
            ->method('createCheckoutDataValidatorPluginStrategyResolver')
            ->willReturn($strategyResolver);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockShipmentFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockShipmentFacade = $this->createPartialMock(
            ShipmentFacade::class,
            ['getAvailableMethodsByShipment'],
        );
        $mockShipmentFacade
            ->method('getAvailableMethodsByShipment')
            ->willReturn($this->tester->createShipmentMethodsCollectionTransfer());

        $mockCheckoutRestApiFactory
            ->method('getShipmentFacade')
            ->willReturn(
                new CheckoutRestApiToShipmentFacadeBridge(
                    $mockShipmentFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCartFacade = $this->createPartialMock(
            CartFacade::class,
            ['validateQuote'],
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->tester->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     * @param \Generated\Shared\Transfer\QuoteProcessFlowTransfer|null $quoteProcessFlowTransfer
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartsRestApiFacade(
        CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory,
        ?QuoteProcessFlowTransfer $quoteProcessFlowTransfer = null
    ): CheckoutRestApiBusinessFactory {
        $mockCartsRestApiFacade = $this->createPartialMock(
            CartsRestApiFacade::class,
            ['findQuoteByUuid'],
        );
        $mockCartsRestApiFacade
            ->method('findQuoteByUuid')
            ->willReturn($this->tester->createQuoteResponseTransfer($quoteProcessFlowTransfer));

        $mockCheckoutRestApiFactory
            ->method('getCartsRestApiFacade')
            ->willReturn(
                new CheckoutRestApiToCartsRestApiFacadeBridge(
                    $mockCartsRestApiFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartsRestApiFacadeForGuest(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCartsRestApiFacade = $this->createPartialMock(
            CartsRestApiFacade::class,
            ['findQuoteByUuid'],
        );
        $mockCartsRestApiFacade
            ->method('findQuoteByUuid')
            ->willReturn($this->tester->createQuoteResponseForGuestTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartsRestApiFacade')
            ->willReturn(
                new CheckoutRestApiToCartsRestApiFacadeBridge(
                    $mockCartsRestApiFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockConfig(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiConfig = $this->createPartialMock(
            CheckoutRestApiConfig::class,
            ['deleteCartAfterOrderCreation'],
        );
        $mockCheckoutRestApiConfig
            ->method('deleteCartAfterOrderCreation')
            ->willReturn(true);

        $mockCheckoutRestApiFactory
            ->method('getConfig')
            ->willReturn($mockCheckoutRestApiConfig);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCalculationFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCartsRestApiFacade = $this->createPartialMock(
            CalculationFacade::class,
            ['recalculateQuote'],
        );
        $mockCartsRestApiFacade
            ->method('recalculateQuote')
            ->willReturn($this->tester->createQuoteTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCalculationFacade')
            ->willReturn(
                new CheckoutRestApiToCalculationFacadeBridge(
                    $mockCartsRestApiFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacadeWithFailingValidation(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCartFacade = $this->createPartialMock(
            CartFacade::class,
            ['validateQuote'],
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->tester->createQuoteResponseTransferWithFailingValidation());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCheckoutFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutFacade = $this->createPartialMock(
            CheckoutFacade::class,
            ['placeOrder'],
        );
        $mockCheckoutFacade
            ->method('placeOrder')
            ->willReturn($this->tester->createCheckoutResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCheckoutFacade')
            ->willReturn(
                new CheckoutRestApiToCheckoutFacadeBridge(
                    $mockCheckoutFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockPaymentFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockPaymentFacade = $this->createPartialMock(
            PaymentFacade::class,
            [
                'getAvailablePaymentProvidersForStore',
                'getAvailableMethods',
            ],
        );
        $mockPaymentFacade
            ->method('getAvailablePaymentProvidersForStore')
            ->willReturn($this->tester->createPaymentProviderCollectionTransfer());
        $mockPaymentFacade
            ->method('getAvailableMethods')
            ->willReturn($this->tester->createAvailableMethodsCollectionTransfer());

        $mockCheckoutRestApiFactory
            ->method('getPaymentFacade')
            ->willReturn(
                new CheckoutRestApiToPaymentFacadeBridge(
                    $mockPaymentFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            [
                'findCustomerByReference',
            ],
        );
        $mockCustomerFacade
            ->method('findCustomerByReference')
            ->willReturn($this->tester->createCustomerResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacadeForGuest(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['findCustomerByReference'],
        );
        $mockCustomerFacade
            ->method('findCustomerByReference')
            ->willReturn((new CustomerResponseBuilder(['isSuccess' => false, 'hasCustomer' => false]))->build());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockQuoteFacade = $this->createPartialMock(
            QuoteFacade::class,
            ['deleteQuote'],
        );
        $mockQuoteFacade
            ->method('deleteQuote')
            ->willReturn($this->tester->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getQuoteFacade')
            ->willReturn(
                new CheckoutRestApiToQuoteFacadeBridge(
                    $mockQuoteFacade,
                ),
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteMapperPlugins(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory
            ->method('getQuoteMapperPlugins')
            ->willReturn(
                [
                    $this->createMockCustomerMapperPlugin(),
                ],
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockCustomerMapperPlugin(): QuoteMapperPluginInterface
    {
        $mockCustomerMapperPlugin = $this->createPartialMock(
            QuoteMapperPluginInterface::class,
            ['map'],
        );
        $mockCustomerMapperPlugin
            ->method('map')
            ->willReturn($this->tester->createQuote());

        return $mockCustomerMapperPlugin;
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteProcessFlowTransfer|null>>
     */
    protected function placeOrderExecutesCheckoutDataValidatorPluginStackWhenItIsSingleDimensional(): array
    {
        $checkoutContext = CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT;
        $orderAmendmentContext = SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT;
        $wrongContext = 'wrong-context';

        return [
            'Executes default stack when flow name is not defind' => [null],
            'Executes default stack when flow name is set' => [(new QuoteProcessFlowTransfer())->setName($checkoutContext)],
            'Executes default stack when flow name is not set' => [(new QuoteProcessFlowTransfer())->setName($orderAmendmentContext)],
            'Executes default stack when flow name is invalid' => [(new QuoteProcessFlowTransfer())->setName($wrongContext)],
        ];
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\QuoteProcessFlowTransfer|null>>
     */
    protected function placeOrderExecutesCheckoutDataValidatorPluginStackWhenItIsMultiDimensional(): array
    {
        $checkoutContext = CheckoutExtensionContextsInterface::CONTEXT_CHECKOUT;
        $orderAmendmentContext = SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT;
        $wrongContext = 'wrong-context';

        return [
            'Executes default stack when flow name is not set' => [null, $checkoutContext, $orderAmendmentContext],
            'Executes default stack when flow is not found by name' => [
                (new QuoteProcessFlowTransfer())->setName($wrongContext),
                $checkoutContext,
                $orderAmendmentContext,
            ],
            'Executes default stack when default flow is found by name' => [
                (new QuoteProcessFlowTransfer())->setName($checkoutContext),
                $checkoutContext,
                $orderAmendmentContext,
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface
     */
    protected function getCheckoutDataValidatorPluginMock(): CheckoutDataValidatorPluginInterface
    {
        $checkoutDataValidatorPluginMock = $this
            ->getMockBuilder(CheckoutDataValidatorPluginInterface::class)
            ->getMock();
        $checkoutDataValidatorPluginMock->method('validateCheckoutData')->willReturn(
            (new CheckoutResponseTransfer())->setIsSuccess(true),
        );
        $checkoutDataValidatorPluginMock->expects($this->once())->method('validateCheckoutData');

        return $checkoutDataValidatorPluginMock;
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutDataValidatorPluginInterface
     */
    protected function getNeverCalledCheckoutDataValidatorPluginMock(): CheckoutDataValidatorPluginInterface
    {
        $checkoutDataValidatorPluginMock = $this
            ->getMockBuilder(CheckoutDataValidatorPluginInterface::class)
            ->getMock();
        $checkoutDataValidatorPluginMock->expects($this->never())->method('validateCheckoutData');

        return $checkoutDataValidatorPluginMock;
    }
}
