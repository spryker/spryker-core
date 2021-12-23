<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
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
        return $this->getLocator()
            ->store()
            ->facade()
            ->getStoreByName('DE');
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyTransfer(): CurrencyTransfer
    {
        return $this->getLocator()
            ->currency()
            ->facade()
            ->getDefaultCurrencyForCurrentStore();
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
