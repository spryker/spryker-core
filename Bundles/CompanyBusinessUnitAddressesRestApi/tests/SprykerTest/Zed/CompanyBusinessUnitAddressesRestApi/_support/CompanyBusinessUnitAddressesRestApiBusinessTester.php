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
use Generated\Shared\DataBuilder\CompanyUserBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutDataBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * Inherited Methods
 *
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

    /**
     * @var string
     */
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS1 = 'Address1';

    /**
     * @var string
     */
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS2 = 'Address2';

    /**
     * @var string
     */
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1 = 'fake-company-business-unit-address-uuid1';

    /**
     * @var string
     */
    public const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID2 = 'fake-company-business-unit-address-uuid2';

    /**
     * @var int
     */
    protected const FAKE_ID_COMPANY = 555;

    /**
     * @var int
     */
    protected const FAKE_ID_COMPANY_BUSINESS_UNIT = 777;

    /**
     * @var int
     */
    protected const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_ID = 12345;

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock
     *
     * @return \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiFacadeInterface
     */
    public function getFacadeMock(MockObject $companyBusinessUnitAddressesRestApiBusinessFactoryMock): CompanyBusinessUnitAddressesRestApiFacadeInterface
    {
        $container = new Container();
        $companyBusinessUnitAddressesRestApiDependencyProvider = new CompanyBusinessUnitAddressesRestApiDependencyProvider();
        $companyBusinessUnitAddressesRestApiDependencyProvider->provideBusinessLayerDependencies($container);

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock->setContainer($container);

        $companyBusinessUnitAddressesRestApiFacadeMock = $this->getFacade();
        $companyBusinessUnitAddressesRestApiFacadeMock->setFactory($companyBusinessUnitAddressesRestApiBusinessFactoryMock);

        return $companyBusinessUnitAddressesRestApiFacadeMock;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function createCompanyUnitAddressCollectionTransfer(): CompanyUnitAddressCollectionTransfer
    {
        return (new CompanyUnitAddressCollectionBuilder())
            ->withCompanyUnitAddress([CompanyUnitAddressTransfer::UUID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1])
            ->withAnotherCompanyUnitAddress([CompanyUnitAddressTransfer::UUID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID2])
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
                CompanyUnitAddressTransfer::UUID => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1,
                CompanyUnitAddressTransfer::ADDRESS1 => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS1,
                CompanyUnitAddressTransfer::ADDRESS2 => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS2,
                CompanyUnitAddressTransfer::COMPANY => (new CompanyBuilder([CompanyTransfer::ID_COMPANY => static::FAKE_ID_COMPANY]))->build(),
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
            ->withBillingAddress([RestAddressTransfer::ID_COMPANY_BUSINESS_UNIT_ADDRESS => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1])
            ->withShippingAddress([RestAddressTransfer::ID_COMPANY_BUSINESS_UNIT_ADDRESS => static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID1])
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        $customerTransfer = (new CustomerBuilder([
            CustomerTransfer::COMPANY_USER_TRANSFER => (new CompanyUserBuilder([
                CompanyUserTransfer::COMPANY => (new CompanyBuilder([
                    CompanyTransfer::ID_COMPANY => static::FAKE_ID_COMPANY,
                ]))->build(),
            ]))->build(),
        ]))->build();

        return (new QuoteBuilder())
            ->withCustomer($customerTransfer->toArray())
            ->withItem([
                ItemTransfer::SHIPMENT => (new ShipmentBuilder())->build(),
            ])
            ->withAnotherItem([
                ItemTransfer::SHIPMENT => (new ShipmentBuilder())->build(),
            ])
            ->build();
    }
}
