<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequest;

use Codeception\Actor;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
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
 * @SuppressWarnings(\SprykerTest\Zed\QuoteRequest\PHPMD)
 */
class QuoteRequestBusinessTester extends Actor
{
    use _generated\QuoteRequestBusinessTesterActions;

    /**
     * @var string
     */
    public const FAKE_QUOTE_REQUEST_REFERENCE = 'FAKE_QUOTE_REQUEST_REFERENCE';

    /**
     * @var string
     */
    public const FAKE_QUOTE_REQUEST_VERSION_REFERENCE = 'FAKE_QUOTE_REQUEST_VERSION_REFERENCE';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS
     *
     * @var string
     */
    public const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS
     *
     * @var string
     */
    public const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @see \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserWriter::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS
     *
     * @var string
     */
    public const GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS = 'quote_request.validation.error.empty_quote_items';

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
     * @param array $metadata
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function createQuoteRequestVersion(QuoteTransfer $quoteTransfer, array $metadata = []): QuoteRequestVersionTransfer
    {
        return $this->haveQuoteRequestVersion([
            QuoteRequestVersionTransfer::QUOTE => $quoteTransfer,
            QuoteRequestVersionTransfer::METADATA => $metadata,
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
            ],
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
            ],
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
            ],
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithCustomer(CustomerTransfer $customerTransfer): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withItem([ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(), ItemTransfer::UNIT_PRICE => 1, ItemTransfer::QUANTITY => 1])
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $metadata
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInDraftStatus(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        array $metadata = []
    ): QuoteRequestTransfer {
        return $this->createQuoteRequest(
            $this->createQuoteRequestVersion($quoteTransfer, $metadata),
            $companyUserTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequest
     *
     * @return void
     */
    public function addCleanupQuoteRequest(QuoteRequestTransfer $quoteRequest): void
    {
        $this->addCleanup(function () use ($quoteRequest): void {
            $this->getFacade()->deleteQuoteRequestsByIdCompanyUser($quoteRequest->getCompanyUser()->getIdCompanyUser());
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInWaitingStatus(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInDraftStatus($companyUserTransfer, $quoteTransfer);

        $quoteRequest = $this->getFacade()
            ->sendQuoteRequestToUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $this->addCleanup(function () use ($quoteRequest): void {
            $this->getFacade()->deleteQuoteRequestsByIdCompanyUser($quoteRequest->getCompanyUser()->getIdCompanyUser());
        });

        return $quoteRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $validUntil
     * @param bool|null $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInInProgressStatus(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        ?string $validUntil = null,
        ?bool $isLatestVersionVisible = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInWaitingStatus($companyUserTransfer, $quoteTransfer);

        $quoteRequestTransfer = $this->getFacade()
            ->reviseQuoteRequestForCompanyUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $quoteRequestTransfer
            ->setValidUntil($validUntil)
            ->setIsLatestVersionVisible($isLatestVersionVisible);

        if ($validUntil || !$isLatestVersionVisible) {
            return $this->getFacade()
                ->updateQuoteRequestForCompanyUser($quoteRequestTransfer)
                ->getQuoteRequest();
        }

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $validUntil
     * @param bool|null $isLatestVersionVisible
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInReadyStatus(
        CompanyUserTransfer $companyUserTransfer,
        QuoteTransfer $quoteTransfer,
        ?string $validUntil = null,
        ?bool $isLatestVersionVisible = null
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $this->haveQuoteRequestInInProgressStatus(
            $companyUserTransfer,
            $quoteTransfer,
            $validUntil,
            $isLatestVersionVisible,
        );

        $quoteRequest = $this->getFacade()
            ->sendQuoteRequestToCompanyUser($this->createFilterTransfer($quoteRequestTransfer))
            ->getQuoteRequest();

        $this->addCleanup(function () use ($quoteRequestTransfer): void {
            $this->getFacade()->deleteQuoteRequestsByIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
        });

        return $quoteRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer
     */
    public function createFilterTransfer(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestFilterTransfer
    {
        return (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
    }
}
