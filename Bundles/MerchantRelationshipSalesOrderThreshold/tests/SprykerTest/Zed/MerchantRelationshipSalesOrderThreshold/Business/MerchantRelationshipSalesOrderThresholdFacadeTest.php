<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMinimumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithMessageStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipSalesOrderThreshold
 * @group Business
 * @group Facade
 * @group MerchantRelationshipSalesOrderThresholdFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipSalesOrderThresholdFacadeTest extends MerchantRelationshipSalesOrderThresholdMocks
{
    protected const HARD_STRATEGY_KEY = 'hard-minimum-threshold';
    protected const SOFT_STRATEGY_KEY = 'soft-minimum-threshold';
    protected const MERCHANT_RELATIONSHIP_KEY = 'mr-test-001';

    /**
     * @var \SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface[]
     */
    protected $strategies;

    /**
     * @return void
     */
    public function testSaveMerchantRelationshipHardAndSoftThresholds(): void
    {
        $this->setupDependencies();

        $merchantRelationshipTransfer = $this->tester->createTestMerchantRelationship(static::MERCHANT_RELATIONSHIP_KEY);

        $storeTransferDE = $this->tester->createTestStoreTransfer();
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = $this->tester->createTestCurrencyTransfer();
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $this->tester->createTestMerchantRelationshipSalesOrderThresholdTransfer(
                static::HARD_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                10000
            )
        );

        $hardThreshold2 = $this->getFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $this->tester->createTestMerchantRelationshipSalesOrderThresholdTransfer(
                static::HARD_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold1 = $this->getFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $this->tester->createTestMerchantRelationshipSalesOrderThresholdTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold2 = $this->getFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $this->tester->createTestMerchantRelationshipSalesOrderThresholdTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold3 = $this->getFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $this->tester->createTestMerchantRelationshipSalesOrderThresholdTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );

        // Assert
        $this->assertEquals($hardThreshold1->getIdMerchantRelationshipSalesOrderThreshold(), $hardThreshold2->getIdMerchantRelationshipSalesOrderThreshold());
        $this->assertNotEquals($hardThreshold1->getIdMerchantRelationshipSalesOrderThreshold(), $softThreshold1->getIdMerchantRelationshipSalesOrderThreshold());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipSalesOrderThreshold(), $softThreshold2->getIdMerchantRelationshipSalesOrderThreshold());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipSalesOrderThreshold(), $softThreshold3->getIdMerchantRelationshipSalesOrderThreshold());
        $this->assertNotEquals($softThreshold2->getIdMerchantRelationshipSalesOrderThreshold(), $softThreshold3->getIdMerchantRelationshipSalesOrderThreshold());

        // Cleanup
        $this->tester->cleanupMerchantRelationshipSalesOrderThresholds();
    }

    /**
     * @return void
     */
    public function testFindApplicableThresholds(): void
    {
        // Prepare
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Action
        $this->getFacade()->findApplicableThresholds($quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @return void
     */
    protected function setupDependencies(): void
    {
        $this->strategies = [
            new HardMinimumThresholdStrategyPlugin(),
            new SoftMinimumThresholdWithMessageStrategyPlugin(),
        ];

        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, $this->strategies);
    }
}
