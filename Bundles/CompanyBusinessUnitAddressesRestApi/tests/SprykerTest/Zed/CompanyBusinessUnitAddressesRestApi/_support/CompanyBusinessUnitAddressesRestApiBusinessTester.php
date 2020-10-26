<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyUnitAddressCollectionBuilder;
use Generated\Shared\DataBuilder\CompanyUnitAddressResponseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutDataBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
*/
class CompanyBusinessUnitAddressesRestApiBusinessTester extends Actor
{
    use _generated\CompanyBusinessUnitAddressesRestApiBusinessTesterActions;

    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS1 = 'Address1';
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS2 = 'Address2';
    public const FAKE_ID_COMPANY_BUSINESS_UNIT = 777;
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID = 'fake-company-business-unit-address-uuid';
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_ID = 12345;

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function createCompanyUnitAddressCollectionTransfer(): CompanyUnitAddressCollectionTransfer
    {
        return (new CompanyUnitAddressCollectionBuilder())
            ->withCompanyUnitAddress()
            ->withAnotherCompanyUnitAddress()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function createCompanyUnitAddressResponseTransfer(): CompanyUnitAddressResponseTransfer
    {
        return (new CompanyUnitAddressResponseBuilder([CompanyUnitAddressResponseTransfer::IS_SUCCESSFUL => true]))
            ->withCompanyUnitAddressTransfer([
                CompanyUnitAddressTransfer::ID_COMPANY_UNIT_ADDRESS => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_ID,
                CompanyUnitAddressTransfer::UUID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID,
                CompanyUnitAddressTransfer::ADDRESS1 => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS1,
                CompanyUnitAddressTransfer::ADDRESS2 => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS2,
                CompanyUnitAddressTransfer::COMPANY => (new CompanyBuilder())->build()
            ])
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function createRestCheckoutDataTransfer(): RestCheckoutDataTransfer
    {
        return (new RestCheckoutDataBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createRestCheckoutRequestAttributesTransfer(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder())
            ->withCustomer([RestCustomerTransfer::ID_COMPANY_BUSINESS_UNIT => static::FAKE_ID_COMPANY_BUSINESS_UNIT])
            ->withBillingAddress([RestAddressTransfer::COMPANY_BUSINESS_UNIT_ADDRESS_ID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID])
            ->withShippingAddress([RestAddressTransfer::COMPANY_BUSINESS_UNIT_ADDRESS_ID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID])
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withCustomer()
            ->withItem([
                ItemTransfer::SHIPMENT => (new ShipmentBuilder())->build(),
            ])
            ->withAnotherItem([
                ItemTransfer::SHIPMENT => (new ShipmentBuilder())->build(),
            ])
            ->build();
    }
}
