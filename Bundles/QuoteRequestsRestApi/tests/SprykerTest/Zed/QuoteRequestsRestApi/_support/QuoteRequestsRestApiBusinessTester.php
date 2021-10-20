<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteRequestsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ConfiguredBundleBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
 * @method \Spryker\Zed\QuoteRequestsRestApi\Business\QuoteRequestsRestApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class QuoteRequestsRestApiBusinessTester extends Actor
{
    use _generated\QuoteRequestsRestApiBusinessTesterActions;

    /**
     * @var string
     */
    public const FAKE_CUSTOMER_REFERENCE = 'FAKE_CUSTOMER_REFERENCE';

    /**
     * @var string
     */
    public const FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY = 'configurable-bundle-group-key';

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
        $companyBusinessUnitTransfer = $this->createCompanyBusinessUnit($companyTransfer);

        return $this->haveCompanyUser(
            [
                CompanyUserTransfer::CUSTOMER => $customerTransfer,
                CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return $this->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->buildCustomerTransfer(static::FAKE_CUSTOMER_REFERENCE, $this->createCompanyUser($this->haveCustomer())),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->buildConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
                [
                    ItemTransfer::SKU => (new ProductConcreteBuilder())->build()->getSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::CONFIGURED_BUNDLE => $this->buildConfiguredBundleTransfer(static::FAKE_CONFIGURABLE_BUNDLE_GROUP_KEY),
                ],
            ],
        ]);
    }

    /**
     * @param string|null $customerReference
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function buildCustomerTransfer(?string $customerReference, CompanyUserTransfer $companyUserTransfer): CustomerTransfer
    {
        return (new CustomerBuilder())->build()->setCompanyUserTransfer($companyUserTransfer)
            ->setCustomerReference($customerReference);
    }

    /**
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    protected function buildConfiguredBundleTransfer(?string $groupKey = null): ConfiguredBundleTransfer
    {
        return (new ConfiguredBundleBuilder())->build()
            ->setGroupKey($groupKey);
    }
}
