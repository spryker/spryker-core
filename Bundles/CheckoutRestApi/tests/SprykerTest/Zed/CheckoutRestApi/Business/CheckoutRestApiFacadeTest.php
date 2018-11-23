<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressesBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutResponseTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use PHPUnit\Framework\MockObject\MockObject;
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
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\Quote\Business\QuoteFacade;
use Spryker\Zed\Shipment\Business\ShipmentFacade;

/**
 * Auto-generated group annotations
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
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransfer()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactory();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $restCheckoutDataResponseTransfer = $checkoutRestApiFacade->getCheckoutData($restCheckoutRequestAttributesTransfer);

        $this->assertNotEmpty($restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(RestCheckoutDataResponseTransfer::class, $restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods());
        $this->assertInstanceOf(PaymentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentMethods());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses()->getAddresses());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods()->getMethods());
        $this->assertCount(2, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentMethods()->getMethods());
    }

    /**
     * @return void
     */
    public function testGetCheckoutDataWillReturnNotEmptyCheckoutDataTransferForGuest()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryForGuest();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);
        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $restCheckoutDataResponseTransfer = $checkoutRestApiFacade->getCheckoutData($restCheckoutRequestAttributesTransfer);

        $this->assertNotEmpty($restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(RestCheckoutDataResponseTransfer::class, $restCheckoutDataResponseTransfer);
        $this->assertInstanceOf(AddressesTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses());
        $this->assertInstanceOf(ShipmentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods());
        $this->assertInstanceOf(PaymentMethodsTransfer::class, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentMethods());
        $this->assertCount(0, $restCheckoutDataResponseTransfer->getCheckoutData()->getAddresses()->getAddresses());
        $this->assertCount(1, $restCheckoutDataResponseTransfer->getCheckoutData()->getShipmentMethods()->getMethods());
        $this->assertCount(2, $restCheckoutDataResponseTransfer->getCheckoutData()->getPaymentMethods()->getMethods());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForCustomer()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactory();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillPlaceOrderForGuest()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryForGuest();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransferForGuest();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPlaceOrderWillFailOnItemOutOfStock()
    {
        /**
         * @var \Spryker\Zed\CheckoutRestApi\Business\CheckoutRestApiFacade $checkoutRestApiFacade
         */
        $checkoutRestApiFacade = $this->tester->getFacade();
        $mockCheckoutRestApiFactory = $this->getMockCheckoutRestApiFactoryWithFailingValidation();
        $checkoutRestApiFacade->setFactory($mockCheckoutRestApiFactory);

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();

        $checkoutResponseTransfer = $checkoutRestApiFacade->placeOrder($restCheckoutRequestAttributesTransfer);

        $this->assertInstanceOf(RestCheckoutResponseTransfer::class, $checkoutResponseTransfer);
        $this->assertNotTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function initMockCheckoutRestApiFactory(): MockObject
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
                'getQuoteMappingPlugins',
                'getCalculationFacade',
            ]
        );

        $mockCheckoutRestApiFactory = $this->addMockShipmentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockPaymentFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCheckoutFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockQuoteFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartsRestApiFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCalculationFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactory(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryWithFailingValidation(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacade($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacadeWithFailingValidation($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCheckoutRestApiFactoryForGuest(): MockObject
    {
        $mockCheckoutRestApiFactory = $this->initMockCheckoutRestApiFactory();
        $mockCheckoutRestApiFactory = $this->addMockCustomerFacadeForGuest($mockCheckoutRestApiFactory);
        $mockCheckoutRestApiFactory = $this->addMockCartFacade($mockCheckoutRestApiFactory);

        return $mockCheckoutRestApiFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockShipmentFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockShipmentFacade = $this->createPartialMock(
            ShipmentFacade::class,
            ['getAvailableMethods']
        );
        $mockShipmentFacade
            ->method('getAvailableMethods')
            ->willReturn($this->tester->createShipmentMethodsTransfer());

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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacade(MockObject $mockCheckoutRestApiFactory): MockObject
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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartsRestApiFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCartsRestApiFacade = $this->createPartialMock(
            CartsRestApiFacade::class,
            ['findCustomerQuoteByUuid']
        );
        $mockCartsRestApiFacade
            ->method('findCustomerQuoteByUuid')
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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCalculationFacade(MockObject $mockCheckoutRestApiFactory): MockObject
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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCartFacadeWithFailingValidation(MockObject $mockCheckoutRestApiFactory): MockObject
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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCheckoutFacade(MockObject $mockCheckoutRestApiFactory): MockObject
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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockPaymentFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockPaymentFacade = $this->createPartialMock(
            PaymentFacade::class,
            ['getAvailableMethods']
        );
        $mockPaymentFacade
            ->method('getAvailableMethods')
            ->willReturn($this->tester->createPaymentMethodsTransfer());

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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacade(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            [
                'getAddresses',
            ]
        );
        $mockCustomerFacade
            ->method('getAddresses')
            ->willReturn($this->tester->createAddressesTransfer());

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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockCustomerFacadeForGuest(MockObject $mockCheckoutRestApiFactory): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['getAddresses']
        );
        $mockCustomerFacade
            ->method('getAddresses')
            ->willReturn((new AddressesBuilder())->build());

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
     * @param \PHPUnit\Framework\MockObject\MockObject $mockCheckoutRestApiFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockQuoteFacade(MockObject $mockCheckoutRestApiFactory): MockObject
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
}
