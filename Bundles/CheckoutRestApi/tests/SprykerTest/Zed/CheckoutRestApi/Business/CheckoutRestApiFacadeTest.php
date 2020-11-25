<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerResponseBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\CartsRestApi\Business\CartsRestApiFacade;
use Spryker\Zed\Checkout\Business\CheckoutFacade;
use Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCalculationFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCartsRestApiFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCheckoutFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToPaymentFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToQuoteFacadeBridge;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToShipmentFacadeBridge;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Customer\Business\CustomerFacade;
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
        $checkoutRestApiFacade = $this->tester->getFacade();
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
                'getCheckoutDataValidatorPlugins',
                'getCheckoutValidatorPlugins',
                'getCalculationFacade',
                'getCheckoutDataExpanderPlugins',
            ]
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockQuoteFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockQuoteMapperPlugins($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCalculationFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactory(): CheckoutRestApiBusinessFactory
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacade($mockCheckoutRestApiFactory);

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
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacadeForGuest($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacadeForGuest($mockCheckoutRestApiFactory);

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
            ['getAvailableMethodsByShipment']
        );
        $mockShipmentFacade
            ->method('getAvailableMethodsByShipment')
            ->willReturn($this->tester->createShipmentMethodsCollectionTransfer());

        $mockCheckoutRestApiFactory
            ->method('getShipmentFacade')
            ->willReturn(
                new CheckoutRestApiToShipmentFacadeBridge(
                    $mockShipmentFacade
                )
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
            ['validateQuote']
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->tester->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade
                )
            );

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartsRestApiFacade(CheckoutRestApiBusinessFactory $mockCheckoutRestApiFactory): CheckoutRestApiBusinessFactory
    {
        $mockCartsRestApiFacade = $this->createPartialMock(
            CartsRestApiFacade::class,
            ['findQuoteByUuid']
        );
        $mockCartsRestApiFacade
            ->method('findQuoteByUuid')
            ->willReturn($this->tester->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartsRestApiFacade')
            ->willReturn(
                new CheckoutRestApiToCartsRestApiFacadeBridge(
                    $mockCartsRestApiFacade
                )
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
            ['findQuoteByUuid']
        );
        $mockCartsRestApiFacade
            ->method('findQuoteByUuid')
            ->willReturn($this->tester->createQuoteResponseForGuestTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCartsRestApiFacade')
            ->willReturn(
                new CheckoutRestApiToCartsRestApiFacadeBridge(
                    $mockCartsRestApiFacade
                )
            );

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
            ['recalculateQuote']
        );
        $mockCartsRestApiFacade
            ->method('recalculateQuote')
            ->willReturn($this->tester->createQuoteTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCalculationFacade')
            ->willReturn(
                new CheckoutRestApiToCalculationFacadeBridge(
                    $mockCartsRestApiFacade
                )
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
            ['validateQuote']
        );
        $mockCartFacade
            ->method('validateQuote')
            ->willReturn($this->tester->createQuoteResponseTransferWithFailingValidation());

        $mockCheckoutRestApiFactory
            ->method('getCartFacade')
            ->willReturn(
                new CheckoutRestApiToCartFacadeBridge(
                    $mockCartFacade
                )
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
            ['placeOrder']
        );
        $mockCheckoutFacade
            ->method('placeOrder')
            ->willReturn($this->tester->createCheckoutResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCheckoutFacade')
            ->willReturn(
                new CheckoutRestApiToCheckoutFacadeBridge(
                    $mockCheckoutFacade
                )
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
            ]
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
                    $mockPaymentFacade
                )
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
            ]
        );
        $mockCustomerFacade
            ->method('findCustomerByReference')
            ->willReturn($this->tester->createCustomerResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade
                )
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
            ['findCustomerByReference']
        );
        $mockCustomerFacade
            ->method('findCustomerByReference')
            ->willReturn((new CustomerResponseBuilder(['isSuccess' => false, 'hasCustomer' => false]))->build());

        $mockCheckoutRestApiFactory
            ->method('getCustomerFacade')
            ->willReturn(
                new CheckoutRestApiToCustomerFacadeBridge(
                    $mockCustomerFacade
                )
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
            ['deleteQuote']
        );
        $mockQuoteFacade
            ->method('deleteQuote')
            ->willReturn($this->tester->createQuoteResponseTransfer());

        $mockCheckoutRestApiFactory
            ->method('getQuoteFacade')
            ->willReturn(
                new CheckoutRestApiToQuoteFacadeBridge(
                    $mockQuoteFacade
                )
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
                ]
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
            ['map']
        );
        $mockCustomerMapperPlugin
            ->method('map')
            ->willReturn($this->tester->createQuote());

        return $mockCustomerMapperPlugin;
    }
}
