<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestShipmentsBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;
use SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitAddressesRestApi
 * @group Business
 * @group Facade
 * @group CompanyBusinessUnitAddressesRestApiFacadeTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitAddressesRestApiFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator\CompanyBusinessUnitAddressValidator::GLOSSARY_KEY_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND = 'checkout.validation.company_address.not_found';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator\CompanyBusinessUnitAddressValidator::GLOSSARY_KEY_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY = 'checkout.validation.company_address.not_applicable';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithCompanyBusinessUnitAddressesWillUseExistingBusinessUnitAddress(): void
    {
        // Arrange
        $companyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $companyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $restCheckoutDataTransfer = $companyBusinessUnitAddressesRestApiFacade->expandCheckoutDataWithCompanyBusinessUnitAddresses(
            $this->tester->createRestCheckoutDataTransfer(),
            $this->tester->createRestCheckoutRequestAttributesTransfer()
        );

        // Assert
        $this->assertInstanceOf(
            CompanyUnitAddressCollectionTransfer::class,
            $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses(),
            'Expected `RestCheckoutDataTransfer` to contain `CompanyUnitAddressCollectionTransfer`.'
        );

        $this->assertCount(
            2,
            $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses()->getCompanyUnitAddresses(),
            'Expected `CompanyUnitAddressCollectionTransfer` to contain two `CompanyUnitAddressTransfer`.'
        );
    }

    /**
     * @return void
     */
    public function testMapCompanyBusinessUnitAddressesToQuoteWillMapExistingBusinessUnitAddress(): void
    {
        // Arrange
        $companyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $companyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $quoteTransfer = $companyBusinessUnitAddressesRestApiFacade->mapCompanyBusinessUnitAddressesToQuote(
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
     * @return void
     */
    public function testValidateCompanyBusinessUnitAddressesInCheckoutDataWillNotReturnErrorIfNoShippingAddressProvided(): void
    {
        // Arrange
        $checkoutDataTransfer = (new CheckoutDataBuilder([CheckoutDataTransfer::SHIPMENTS => []]))->build();

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()
            ->validateCompanyBusinessUnitAddressesInCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCompanyBusinessUnitAddressesInCheckoutDataWillNotReturnErrorIfCorrectShippingAddressProvided(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder())
            ->withCompanyUserTransfer([CompanyUserTransfer::FK_COMPANY => 1])
            ->build();
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER => $customerTransfer->toArray()]))->build();
        $restShipmentsTransfer = (new RestShipmentsBuilder([
            RestShipmentsTransfer::SHIPPING_ADDRESS => [
                RestAddressTransfer::ID_COMPANY_BUSINESS_UNIT_ADDRESS => CompanyBusinessUnitAddressesRestApiBusinessTester::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1,
            ],
        ]))->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder([
            CheckoutDataTransfer::QUOTE => $quoteTransfer->toArray(),
            CheckoutDataTransfer::SHIPMENTS => [$restShipmentsTransfer->toArray()],
        ]))->build();

        $companyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $companyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $checkoutResponseTransfer = $companyBusinessUnitAddressesRestApiFacade->validateCompanyBusinessUnitAddressesInCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateCompanyBusinessUnitAddressesInCheckoutDataWillReturnErrorIfNoCompanyUserProvided(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder([CustomerTransfer::COMPANY_USER_TRANSFER => null]))->build();
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER => $customerTransfer]))->build();
        $restShipmentsTransfer = (new RestShipmentsBuilder([
            RestShipmentsTransfer::SHIPPING_ADDRESS => [
                RestAddressTransfer::ID_COMPANY_BUSINESS_UNIT_ADDRESS => CompanyBusinessUnitAddressesRestApiBusinessTester::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1,
            ],
        ]))->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder([
            CheckoutDataTransfer::QUOTE => $quoteTransfer->toArray(),
            CheckoutDataTransfer::SHIPMENTS => [$restShipmentsTransfer->toArray()],
        ]))->build();

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateCompanyBusinessUnitAddressesInCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY,
            $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidateCompanyBusinessUnitAddressesInCheckoutDataWillReturnErrorIfNoCorrectShippingAddressProvided(): void
    {
        // Arrange
        $customerTransfer = (new CustomerBuilder())->withCompanyUserTransfer([
                CompanyUserTransfer::FK_COMPANY => 1,
            ])->build();
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::CUSTOMER => $customerTransfer]))->build();
        $restShipmentsTransfer = (new RestShipmentsBuilder([
            RestShipmentsTransfer::SHIPPING_ADDRESS => [
                RestAddressTransfer::ID_COMPANY_BUSINESS_UNIT_ADDRESS => 'random-uuid',
            ],
        ]))->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder([
            CheckoutDataTransfer::QUOTE => $quoteTransfer->toArray(),
            CheckoutDataTransfer::SHIPMENTS => [$restShipmentsTransfer->toArray()],
        ]))->build();

        $companyBusinessUnitAddressesRestApiFacade = $this->tester->getFacade();
        $companyBusinessUnitAddressesRestApiFacade->setFactory(
            $this->getCompanyBusinessUnitAddressesRestApiBusinessFactoryMock()
        );

        // Act
        $checkoutResponseTransfer = $companyBusinessUnitAddressesRestApiFacade->validateCompanyBusinessUnitAddressesInCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND,
            $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage()
        );
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
            'Expected company unit address has idCompanyUnitAddress set.'
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
