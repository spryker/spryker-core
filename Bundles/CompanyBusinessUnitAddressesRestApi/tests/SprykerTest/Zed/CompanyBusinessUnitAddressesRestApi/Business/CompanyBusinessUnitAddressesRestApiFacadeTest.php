<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;
use SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester;

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
class CompanyBusinessUnitAddressesRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester
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
    public function testExpandCheckoutDataWithCompanyBusinessUnitAddresses(): void
    {
        // Arrange
        $CompanyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $CompanyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $restCheckoutDataTransfer = $CompanyBusinessUnitAddressesRestApiFacade->expandCheckoutDataWithCompanyBusinessUnitAddresses(
            $this->tester->createRestCheckoutDataTransfer(),
            $this->tester->createRestCheckoutRequestAttributesTransfer()
        );

        // Assert
        $this->assertInstanceOf(
            CompanyUnitAddressCollectionTransfer::class,
            $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses(),
            'Expected `RestCheckoutDataTransfer` contains `CompanyUnitAddressCollectionTransfer`.'
        );

        $this->assertCount(
            2,
            $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses()->getCompanyUnitAddresses(),
            'Expected `CompanyUnitAddressCollectionTransfer` contains two `CompanyUnitAddressTransfer`.'
        );
    }

    /**
     * @return void
     */
    public function testMapCompanyBusinessUnitAddressesToQuote(): void
    {
        // Arrange
        $CompanyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $CompanyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $quoteTransfer = $CompanyBusinessUnitAddressesRestApiFacade->mapCompanyBusinessUnitAddressesToQuote(
            $this->tester->createRestCheckoutRequestAttributesTransfer(),
            $this->tester->createQuoteTransfer()
        );

        // Assert
        $this->assertCompanyBusinessUnitAddress($quoteTransfer->getBillingAddress(), $quoteTransfer);
        $this->assertCompanyBusinessUnitAddress($quoteTransfer->getShippingAddress(), $quoteTransfer);
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertCompanyBusinessUnitAddress(
                $itemTransfer->getShipment()->getShippingAddress(),
                $quoteTransfer
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertCompanyBusinessUnitAddress(AddressTransfer $addressTransfer, QuoteTransfer $quoteTransfer): void
    {
        $this->assertIsInt(
            $addressTransfer->getIdCompanyUnitAddress(),
            'Expected billing address has idCompanyUnitAddress set.'
        );
        $this->assertTrue(
            $addressTransfer->getIsAddressSavingSkipped(),
            'Expected company business unit address will not be saved.'
        );
        $this->assertSame(
            $quoteTransfer->getCustomer()->getFirstName(),
            $addressTransfer->getFirstName(),
            'Expected address first name has taken from customer.'
        );
        $this->assertSame(
            $quoteTransfer->getCustomer()->getLastName(),
            $addressTransfer->getLastName(),
            'Expected address last name has taken from customer.'
        );
        $this->assertSame(
            CompanyBusinessUnitAddressesRestApiBusinessTester::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS1,
            $addressTransfer->getAddress1(),
            'Expected `Address1` field has taken from company business unit address.'
        );
        $this->assertSame(
            CompanyBusinessUnitAddressesRestApiBusinessTester::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS2,
            $addressTransfer->getAddress2(),
            'Expected `Address2` field has taken from company business unit address.'
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock(): CompanyBusinessUnitAddressesRestApiBusinessFactory
    {
        $mockCompanyBusinessUnitAddressesRestApiBusinessFactory = $this->createPartialMock(
            CompanyBusinessUnitAddressesRestApiBusinessFactory::class,
            ['getCompanyUnitAddressFacade']
        );

        $mockCompanyBusinessUnitAddressesRestApiBusinessFactory
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock());

        return $mockCompanyBusinessUnitAddressesRestApiBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCompanyUnitAddressFacadeMock(): CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
    {
        $mockCompanyUnitAddressFacade = $this->createPartialMock(
            CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeBridge::class,
            [
                'getCompanyUnitAddressCollection',
                'findCompanyBusinessUnitAddressByUuid',
            ]
        );

        $mockCompanyUnitAddressFacade
            ->method('getCompanyUnitAddressCollection')
            ->willReturn($this->tester->createCompanyUnitAddressCollectionTransfer());

        $mockCompanyUnitAddressFacade
            ->method('findCompanyBusinessUnitAddressByUuid')
            ->willReturn($this->tester->createCompanyUnitAddressResponseTransfer());

        return $mockCompanyUnitAddressFacade;
    }
}
