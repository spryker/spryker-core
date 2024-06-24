<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesMerchantCommission;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

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
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade(?string $moduleName = null)
 */
class SalesMerchantCommissionBusinessTester extends Actor
{
    use _generated\SalesMerchantCommissionBusinessTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const GROSS_MODE = 'GROSS_MODE';

    /**
     * @return void
     */
    public function ensureSalesMerchantCommissionDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesMerchantCommissionQuery(),
        );
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission>
     */
    public function getSalesMerchantCommissions(): ObjectCollection
    {
        return $this->getSalesMerchantCommissionQuery()->find();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotals
     */
    public function getSalesOrderTotalByIdSalesOrder(int $idSalesOrder): SpySalesOrderTotals
    {
        return $this->getSalesOrderTotalsQuery()->filterByFkSalesOrder($idSalesOrder)->find()->getLast();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function getSalesOrderItemByIdSalesOrderItem(int $idSalesOrderItem): SpySalesOrderItem
    {
        return $this->getSalesOrderItemQuery()->filterByIdSalesOrderItem($idSalesOrderItem)->findOne();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Propel\Runtime\Collection\Collection
     */
    public function getSalesOrderItemByIdSalesOrder(int $idSalesOrder): Collection
    {
        return $this->getSalesOrderItemQuery()->filterByFkSalesOrder($idSalesOrder)->find();
    }

    /**
     * @param bool|null $withPriceMode
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithItem(?bool $withPriceMode = false): SaveOrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())->build()->toArray())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']))
            ->setPriceMode($withPriceMode ? static::GROSS_MODE : null);

        return $this->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
    }

    /**
     * @param bool|null $withPriceMode
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithTwoItems(?bool $withPriceMode = false): SaveOrderTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem((new ItemBuilder())->build()->toArray())
            ->withItem((new ItemBuilder())->build()->toArray())
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setStore($this->haveStore([StoreTransfer::NAME => 'DE']))
            ->setPriceMode($withPriceMode ? static::GROSS_MODE : null);

        return $this->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
    }

    /**
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery
     */
    public function getSalesMerchantCommissionQuery(): SpySalesMerchantCommissionQuery
    {
        return SpySalesMerchantCommissionQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderTotalsQuery
     */
    protected function getSalesOrderTotalsQuery(): SpySalesOrderTotalsQuery
    {
        return SpySalesOrderTotalsQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    protected function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }
}
