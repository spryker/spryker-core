<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal;

use Codeception\Actor;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModel;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Shared\Price\PriceMode;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @SuppressWarnings(PHPMD)
 */
class SelfServicePortalBusinessTester extends Actor
{
    use _generated\SelfServicePortalBusinessTesterActions;

    public function getValidBaseQuoteTransfer(PaymentMethodTransfer $paymentMethodTransfer): QuoteTransfer
    {
        $country = new SpyCountry();
        $country->setIso2Code('ix');
        $country->save();

        $currencyTransfer = (new CurrencyTransfer())->setCode('EUR');
        $billingAddress = (new AddressBuilder())->build();
        $shippingAddress = (new AddressBuilder())->build();
        $customerTransfer = (new CustomerBuilder())->build();
        $itemTransfer = (new ItemBuilder())
            ->withShipment()
            ->build();

        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider($paymentMethodTransfer->getPaymentProvider()->getPaymentProviderKey())
            ->setPaymentMethod($paymentMethodTransfer->getPaymentMethodKey())
            ->setPaymentMethodName($paymentMethodTransfer->getName())
            ->setPaymentProviderName($paymentMethodTransfer->getPaymentProvider()->getName())
            ->setPaymentSelection($paymentMethodTransfer->getPaymentMethodKey())
            ->setAmount(1337);

        $shipmentTransfer = (new ShipmentTransfer())
            ->setMethod(new ShipmentMethodTransfer())
            ->setShippingAddress($shippingAddress);

        $totalsTransfer = (new TotalsTransfer())
            ->setGrandTotal(1337)
            ->setSubtotal(337)
            ->setTaxTotal((new TaxTotalTransfer())->setAmount(10));

        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);

        return (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setPriceMode(PriceMode::PRICE_MODE_GROSS)
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totalsTransfer)
            ->setCustomer($customerTransfer)
            ->setShipment($shipmentTransfer)
            ->addItem($itemTransfer)
            ->setPayment($paymentTransfer)
            ->setStore($storeTransfer);
    }

    public function createCompanyUser(?CompanyTransfer $companyTransfer = null): CompanyUserTransfer
    {
        if ($companyTransfer === null) {
            $companyTransfer = $this->haveCompany();
        }

        $customerTransfer = $this->haveCustomer();

        return $this->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY => $companyTransfer,
        ]);
    }

    public function haveCompanyUserWithPermissions(
        CompanyTransfer $companyTransfer,
        PermissionCollectionTransfer $permissionCollectionTransfer
    ): CompanyUserTransfer {
        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $businessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer);

        $customerTransfer = $this->haveCustomer();
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $businessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);

        $companyUserTransfer->setCompanyRoleCollection($companyRoleCollection);
        $companyUserTransfer->setCompany($companyTransfer);
        $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);
        $companyUserTransfer->setCustomer($customerTransfer);

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer;
    }

    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    public function findSalesOrderItemByIdSalesOrderItem(int $idSalesOrderItem): ?SpySalesOrderItem
    {
        return SpySalesOrderItemQuery::create()->findOneByIdSalesOrderItem($idSalesOrderItem);
    }

    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    public function ensureSspModelDatabaseTableIsEmpty(): void
    {
        SpySspModelQuery::create()->deleteAll();
    }

    /**
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspModel>
     */
    public function getSspModelEntities(): array
    {
        return SpySspModelQuery::create()->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     * @param array<\Generated\Shared\Transfer\SspAssetTransfer> $sspAssetTransfers
     *
     * @return \Generated\Shared\Transfer\SspModelTransfer
     */
    public function assignSspAssetsToSspModel(SspModelTransfer $sspModelTransfer, array $sspAssetTransfers): SspModelTransfer
    {
        foreach ($sspAssetTransfers as $sspAssetTransfer) {
            $relationEntity = (new SpySspAssetToSspModel())
            ->setFkSspModel($sspModelTransfer->getIdSspModel())
            ->setFkSspAsset($sspAssetTransfer->getIdSspAsset());

            $relationEntity->save();
        }

        return $sspModelTransfer;
    }

    public function hasSspModelAssetRelation(int $modelId, int $assetId): bool
    {
        return SpySspAssetToSspModelQuery::create()
            ->filterByFkSspModel($modelId)
            ->filterByFkSspAsset($assetId)
            ->exists();
    }
}
