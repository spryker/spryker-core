<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest;

use Codeception\Actor;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\QuoteRequest\Business\QuoteRequestFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class QuoteRequestBusinessTester extends Actor
{
    use _generated\QuoteRequestBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequest(
        QuoteRequestVersionTransfer $quoteRequestVersionTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): QuoteRequestTransfer {
        return $this->haveQuoteRequest([
            QuoteRequestTransfer::LATEST_VERSION => $quoteRequestVersionTransfer,
            QuoteRequestTransfer::COMPANY_USER => $companyUserTransfer,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersion(QuoteTransfer $quoteTransfer): QuoteRequestVersionTransfer
    {
        return $this->haveQuoteRequestVersion([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function createCompanyUser(CustomerTransfer $customerTransfer): CompanyUserTransfer
    {
        $companyTransfer = $this->createCompany();
        $companyBusinessUnit = $this->createCompanyBusinessUnit($companyTransfer);

        return $this->haveCompanyUser(
            [
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
                CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnit->getIdCompanyBusinessUnit(),
                CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            ]
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function createCompany(): CompanyTransfer
    {
        return $this->haveCompany(
            [
                CompanyTransfer::NAME => 'Test company',
                CompanyTransfer::STATUS => 'approved',
                CompanyTransfer::IS_ACTIVE => true,
                CompanyTransfer::INITIAL_USER_TRANSFER => new CompanyUserTransfer(),
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function createCompanyBusinessUnit(CompanyTransfer $companyTransfer): CompanyBusinessUnitTransfer
    {
        return $this->haveCompanyBusinessUnit(
            [
                CompanyBusinessUnitTransfer::NAME => 'test business unit',
                CompanyBusinessUnitTransfer::EMAIL => 'test@spryker.com',
                CompanyBusinessUnitTransfer::PHONE => '1234567890',
                CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            ]
        );
    }

    /**
     * @param int $sourcePrice
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createShipmentWithSourcePrice(int $sourcePrice): ShipmentTransfer
    {
        $shipmentMethodTransfer = $this->haveShipmentMethod();
        $shipmentMethodTransfer->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());
        $shipmentTransfer = (new ShipmentBuilder())->build();

        return $shipmentTransfer->setMethod($shipmentMethodTransfer);
    }
}
