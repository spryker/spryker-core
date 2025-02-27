<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\Plugin\SalesOrderThreshold\MerchantRelationshipSalesOrderThresholdDataSourceStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\SalesOrderThresholdExtension\GlobalSalesOrderThresholdDataSourceStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMaximumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMinimumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFixedFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFlexibleFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithMessageStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;

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
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesOrderThresholdBusinessTester extends Actor
{
    use _generated\SalesOrderThresholdBusinessTesterActions;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setTotals($this->createTotalsTransfer())
            ->setCurrency($this->getCurrencyTransfer())
            ->setStore($this->getStoreTransfer());
    }

    /**
     * @param int $minimumThresholdValue
     * @param int $maximumThresholdValue
     *
     * @return void
     */
    public function setupThresholdDependencies(int $minimumThresholdValue, int $maximumThresholdValue): void
    {
        $salesOrderThresholdStrategies = [
            new HardMinimumThresholdStrategyPlugin(),
            new HardMaximumThresholdStrategyPlugin(),
            new SoftMinimumThresholdWithMessageStrategyPlugin(),
            new SoftMinimumThresholdWithFixedFeeStrategyPlugin(),
            new SoftMinimumThresholdWithFlexibleFeeStrategyPlugin(),
        ];

        $salesOrderThresholdDataSourceStrategies = [
            new MerchantRelationshipSalesOrderThresholdDataSourceStrategyPlugin(),
            new GlobalSalesOrderThresholdDataSourceStrategyPlugin(),
        ];

        $this->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, $salesOrderThresholdStrategies);
        $this->setDependency(SalesOrderThresholdDependencyProvider::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES, $salesOrderThresholdDataSourceStrategies);

        foreach ($salesOrderThresholdStrategies as $salesOrderThresholdStrategy) {
            /** @var \Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\AbstractSalesOrderThresholdStrategyPlugin $salesOrderThresholdStrategy */
            $thresholdValue = $salesOrderThresholdStrategy->getGroup() === SalesOrderThresholdConfig::GROUP_HARD_MAX ? $maximumThresholdValue : $minimumThresholdValue;
            $this->haveSalesOrderThresholdType($salesOrderThresholdStrategy->toTransfer());

            $this->haveSalesOrderThreshold([
                SalesOrderThresholdTransfer::STORE => $this->getStoreTransfer(),
                SalesOrderThresholdTransfer::CURRENCY => $this->getCurrencyTransfer(),
                SalesOrderThresholdTypeTransfer::KEY => $salesOrderThresholdStrategy->getKey(),
                SalesOrderThresholdValueTransfer::THRESHOLD => $thresholdValue,
            ]);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer(): StoreTransfer
    {
        return $this->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransfer(): CurrencyTransfer
    {
        return $this->getLocator()
            ->currency()
            ->facade()
            ->findCurrencyByIsoCode(static::CURRENCY_EUR);
    }

    /**
     * @param int $idOrder
     *
     * @return array<string, \Generated\Shared\Transfer\ExpenseTransfer>
     */
    public function getOrderSalesExpensesIndexedByType(int $idOrder): array
    {
        $expenseTransfers = [];
        $salesExpenseEntities = $this->getSalesExpenseQuery()->filterByFkSalesOrder($idOrder)->find();

        foreach ($salesExpenseEntities as $salesExpenseEntity) {
            $expenseTransfer = (new ExpenseTransfer())->fromArray($salesExpenseEntity->toArray(), true);
            $expenseTransfer->setSumPrice($salesExpenseEntity->getPrice());
            $expenseTransfer->setSumGrossPrice($salesExpenseEntity->getGrossPrice());

            $expenseTransfers[$expenseTransfer->getTypeOrFail()] = $expenseTransfer;
        }

        return $expenseTransfers;
    }

    /**
     * @param string $expenseType
     * @param int $expenseSumPrice
     * @param int $expenseSumGrossPrice
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithExpense(
        string $expenseType,
        int $expenseSumPrice,
        int $expenseSumGrossPrice
    ): QuoteTransfer {
        return (new QuoteTransfer())->addExpense(
            (new ExpenseTransfer())
                ->setType($expenseType)
                ->setSumPrice($expenseSumPrice)
                ->setSumGrossPrice($expenseSumGrossPrice),
        );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpenseQuery
     */
    protected function getSalesExpenseQuery(): SpySalesExpenseQuery
    {
        return SpySalesExpenseQuery::create();
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    protected function createTotalsTransfer(): TotalsTransfer
    {
        return (new TotalsTransfer())
            ->setSubTotal(0);
    }
}
