<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomersRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeBridge;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomersRestApi
 * @group Business
 * @group Facade
 * @group CustomersRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CustomersRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomersRestApi\CustomersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnAllDataProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->tester->assertShippingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);

        $this->assertEquals($restCheckoutRequestAttributesTransfer->getBillingAddress()->getId(), $actualQuote->getBillingAddress()->getUuid());
        $this->assertEquals($restCheckoutRequestAttributesTransfer->getShippingAddress()->getId(), $actualQuote->getShippingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnAllDataProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->tester->assertShippingAddressMappingWithItemLevelShippingAddresses($restCheckoutRequestAttributesTransfer, $actualQuote);

        $this->assertEquals($restCheckoutRequestAttributesTransfer->getBillingAddress()->getId(), $actualQuote->getBillingAddress()->getUuid());
        $this->assertEquals($restCheckoutRequestAttributesTransfer->getShippingAddress()->getId(), $actualQuote->getShippingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnAllDataProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->tester->assertShippingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnAllDataProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareFullGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->tester->assertShippingAddressMappingWithItemLevelShippingAddresses($restCheckoutRequestAttributesTransfer, $actualQuote);
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnOnlyBillingAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyBillingRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);

        $this->assertNull($actualQuote->getShippingAddress());

        $this->assertEquals($restCheckoutRequestAttributesTransfer->getBillingAddress()->getId(), $actualQuote->getBillingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnOnlyBillingAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyBillingRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);

        foreach ($actualQuote->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment());
        }

        $this->assertEquals($restCheckoutRequestAttributesTransfer->getBillingAddress()->getId(), $actualQuote->getBillingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnOnlyBillingAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyBillingGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);

        $this->assertNull($actualQuote->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnOnlyBillingAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyBillingGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertBillingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);

        foreach ($actualQuote->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment());
        }
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnOnlyShippingAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyShippingRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertShippingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->assertNull($actualQuote->getBillingAddress());
        $this->assertEquals($restCheckoutRequestAttributesTransfer->getShippingAddress()->getId(), $actualQuote->getShippingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnOnlyShippingAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyShippingRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertShippingAddressMappingWithItemLevelShippingAddresses($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->assertNull($actualQuote->getBillingAddress());
        $this->assertEquals($restCheckoutRequestAttributesTransfer->getShippingAddress()->getId(), $actualQuote->getShippingAddress()->getUuid());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnOnlyShippingAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyShippingGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertShippingAddressMapping($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->assertNull($actualQuote->getBillingAddress());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnOnlyShippingAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareOnlyShippingGuestRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->tester->assertShippingAddressMappingWithItemLevelShippingAddresses($restCheckoutRequestAttributesTransfer, $actualQuote);
        $this->assertNull($actualQuote->getBillingAddress());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnNoAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getBillingAddress());
        $this->assertNull($actualQuote->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteOnNoAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getBillingAddress());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment()->getShippingAddress());
        }
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnNoAddressProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareGuestCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getBillingAddress());
        $this->assertNull($actualQuote->getShippingAddress());
    }

    /**
     * @return void
     */
    public function testMapAddressesToQuoteWillReturnQuoteForGuestOnNoAddressProvidedWithItemLevelShippingAddresses(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareGuestCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapAddressesToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getBillingAddress());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertNull($itemTransfer->getShipment()->getShippingAddress());
        }
    }

    /**
     * @return void
     */
    public function testMapCustomerToQuoteWillReturnQuoteWithCustomerProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapCustomerToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getCustomer());
        $this->assertNotNull($actualQuote->getCustomerReference());
        $expectedRestCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCustomer();
        $this->assertEquals($expectedRestCustomerTransfer->getCustomerReference(), $actualQuote->getCustomer()->getCustomerReference());
        $this->assertEquals($expectedRestCustomerTransfer->getIdCustomer(), $actualQuote->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testMapCustomerToQuoteWillReturnQuoteForGuestWithCustomerProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactoryForGuest());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareGuestCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapCustomerToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNotNull($actualQuote->getCustomer());
        $this->assertNotNull($actualQuote->getCustomerReference());
        $expectedRestCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCustomer();
        $this->assertEquals($expectedRestCustomerTransfer->getCustomerReference(), $actualQuote->getCustomer()->getCustomerReference());
        $this->assertEquals($expectedRestCustomerTransfer->getIdCustomer(), $actualQuote->getCustomer()->getIdCustomer());
    }

    /**
     * @return void
     */
    public function testMapCustomerToQuoteWillReturnQuoteWithNoCustomerProvided(): void
    {
        /** @var \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacade $customersRestApiFacade */
        $customersRestApiFacade = $this->tester->getFacade();
        $customersRestApiFacade->setFactory($this->getMockCustomersRestApiFactory());

        $restCheckoutRequestAttributesTransfer = $this->tester->prepareNoCustomerRestCheckoutRequestAttributesTransfer();
        $quoteTransfer = $this->tester->prepareQuoteTransfer();

        $actualQuote = $customersRestApiFacade->mapCustomerToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        $this->assertNull($actualQuote->getCustomer());
        $this->assertNull($actualQuote->getCustomerReference());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCustomersRestApiFactory(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            CustomersRestApiBusinessFactory::class,
            ['getCustomerFacade']
        );

        $mockFactory->method('getCustomerFacade')
            ->willReturn(new CustomersRestApiToCustomerFacadeBridge($this->getMockCustomerFacade()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCustomersRestApiFactoryForGuest(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            CustomersRestApiBusinessFactory::class,
            ['getCustomerFacade']
        );

        $mockFactory->method('getCustomerFacade')
            ->willReturn(new CustomersRestApiToCustomerFacadeBridge($this->getMockCustomerFacadeForGuest()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCustomerFacade(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['getAddresses', 'findCustomerByReference']
        );

        $mockCustomerFacade->method('getAddresses')
            ->willReturn(
                (new AddressesTransfer())
                    ->addAddress((new AddressBuilder($this->tester::ADDRESS_1))->build())
                    ->addAddress((new AddressBuilder($this->tester::ADDRESS_2))->build())
            );

        $mockCustomerFacade->method('findCustomerByReference')
            ->willReturn(
                (new CustomerResponseTransfer())
                    ->setIsSuccess(true)
                    ->setHasCustomer(true)
                    ->setCustomerTransfer((new CustomerBuilder($this->tester::CUSTOMER))->build())
            );

        return $mockCustomerFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCustomerFacadeForGuest(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CustomerFacade::class,
            ['getAddresses', 'findCustomerByReference']
        );

        $mockCustomerFacade->method('getAddresses')
            ->willReturn(
                (new AddressesTransfer())
                    ->addAddress((new AddressBuilder($this->tester::ADDRESS_1))->build())
                    ->addAddress((new AddressBuilder($this->tester::ADDRESS_2))->build())
            );

        $mockCustomerFacade->method('findCustomerByReference')
            ->willReturn(
                (new CustomerResponseTransfer())
                    ->setIsSuccess(false)
                    ->setHasCustomer(false)
            );

        return $mockCustomerFacade;
    }
}
