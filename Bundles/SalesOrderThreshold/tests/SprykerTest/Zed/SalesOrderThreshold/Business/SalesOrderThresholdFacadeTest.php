<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdLocalizedMessageTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\HardMinimumThresholdStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFixedFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithFlexibleFeeStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Strategy\SoftMinimumThresholdWithMessageStrategyPlugin;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderThreshold
 * @group Business
 * @group Facade
 * @group SalesOrderThresholdFacadeTest
 * Add your own group annotations below this line
 */
class SalesOrderThresholdFacadeTest extends SalesOrderThresholdMocks
{
    /**
     * @var \SprykerTest\Zed\SalesOrderThreshold\SalesOrderThresholdBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface[]
     */
    protected $strategies;

    /**
     * @return void
     */
    public function testInstallSalesOrderThresholdTypesShouldPersistTypes(): void
    {
        $this->setupDependencies();

        // Act
        $this->getFacade()->installSalesOrderThresholdTypes();

        // Assert
        $this->tester->assertSalesOrderThresholdTypeTableHasRecords(count($this->strategies));
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderThresholdTypeShouldPersistInDatabase(): void
    {
        // Arrange
        $testType = new HardMinimumThresholdStrategyPlugin();
        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, [$testType]);

        // Act
        $returnedTypeTransfer = $this->getFacade()->saveSalesOrderThresholdType($testType->toTransfer());

        // Assert
        $this->assertEquals($testType->toTransfer()->getKey(), $returnedTypeTransfer->getKey());
        $this->assertEquals($testType->toTransfer()->getThresholdGroup(), $returnedTypeTransfer->getThresholdGroup());
    }

    /**
     * @return void
     */
    public function testSaveHardAndSoftSalesOrderThresholds(): void
    {
        $this->setupDependencies();

        // Arrange
        $salesOrderThresholdHardTypeTransfer = $this->findSalesOrderThresholdTypeTransferForGroup(
            SalesOrderThresholdConfig::GROUP_HARD
        );

        $salesOrderThresholdSoftStrategy = $this->findSalesOrderThresholdTypeTransferForGroup(
            SalesOrderThresholdConfig::GROUP_SOFT
        );

        $storeTransferDE = $this->tester->getStoreTransfer();
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = $this->tester->getCurrencyTransfer();
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Act
        $hardThreshold1 = $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                10000
            )
        );

        $hardThreshold2 = $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold1 = $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdSoftStrategy,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold2 = $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdSoftStrategy,
                $storeTransferUS,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold3 = $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdSoftStrategy,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );

        // Assert
        $this->assertEquals($hardThreshold1->getIdSalesOrderThreshold(), $hardThreshold2->getIdSalesOrderThreshold());
        $this->assertNotEquals($hardThreshold1->getIdSalesOrderThreshold(), $softThreshold1->getIdSalesOrderThreshold());
        $this->assertNotEquals($softThreshold1->getIdSalesOrderThreshold(), $softThreshold2->getIdSalesOrderThreshold());
        $this->assertNotEquals($softThreshold1->getIdSalesOrderThreshold(), $softThreshold3->getIdSalesOrderThreshold());
        $this->assertNotEquals($softThreshold2->getIdSalesOrderThreshold(), $softThreshold3->getIdSalesOrderThreshold());
    }

    /**
     * @expectedException \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return void
     */
    public function testSaveSalesOrderThresholdWithInvalidKeyThrowsException(): void
    {
        $this->setupDependencies();

        // Arrange
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');
        $salesOrderThresholdTypeTransferWithWrongKey = (new SalesOrderThresholdTypeTransfer())->setKey('xxxx');

        // Act
        $this->getFacade()->saveSalesOrderThreshold(
            $this->createSalesOrderThresholdTransfer(
                $salesOrderThresholdTypeTransferWithWrongKey,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );
    }

    /**
     * @return void
     */
    public function testSaveSalesOrderThresholdWithLocalizedMessages(): void
    {
        $this->setupDependencies();

        // Arrange
        $salesOrderThresholdSoftStrategy = $this->findSalesOrderThresholdTypeTransferForGroup(
            SalesOrderThresholdConfig::GROUP_SOFT
        );

        $storeTransfer = $this->tester->getStoreTransfer();
        $currencyTransfer = $this->tester->getCurrencyTransfer();

        $salesOrderThresholdTransfer = $this->createSalesOrderThresholdTransfer(
            $salesOrderThresholdSoftStrategy,
            $storeTransfer,
            $currencyTransfer,
            20000
        );

        $salesOrderThresholdTransfer
            ->addLocalizedMessage(
                (new SalesOrderThresholdLocalizedMessageTransfer())
                    ->setLocaleCode('en_US')
                    ->setMessage('Test message')
            );

        // Act
        $softThreshold = $this->getFacade()->saveSalesOrderThreshold(
            $salesOrderThresholdTransfer
        );

        // Assert
        $this->assertNotEmpty($softThreshold->getSalesOrderThresholdValue()->getMessageGlossaryKey());
        $this->assertCount(1, $softThreshold->getLocalizedMessages());
    }

    /**
     * @return void
     */
    public function testGetSalesOrderThresholds(): void
    {
        $this->setupDependencies();

        // Arrange
        $storeTransfer = $this->tester->getStoreTransfer();
        $currencyTransfer = $this->tester->getCurrencyTransfer();
        $thresholdValue = 2000;

        $salesOrderThresholdSoftStrategy = $this->findSalesOrderThresholdTypeTransferForGroup(
            SalesOrderThresholdConfig::GROUP_SOFT
        );

        $salesOrderThresholdTransfer = $this->createSalesOrderThresholdTransfer(
            $salesOrderThresholdSoftStrategy,
            $storeTransfer,
            $currencyTransfer,
            $thresholdValue
        );

        $salesOrderThresholdTransfer
            ->addLocalizedMessage(
                (new SalesOrderThresholdLocalizedMessageTransfer())
                    ->setLocaleCode('en_US')
                    ->setMessage('Test message')
            );

        // Act
        $salesOrderThresholdTransfer = $this->getFacade()->saveSalesOrderThreshold($salesOrderThresholdTransfer);

        // Assert
        $globalThresholds = $this->getFacade()->getSalesOrderThresholds(
            $storeTransfer,
            $currencyTransfer
        );

        foreach ($globalThresholds as $globalThreshold) {
            if ($globalThreshold->getSalesOrderThresholdValue()->getSalesOrderThresholdType() === SalesOrderThresholdConfig::GROUP_SOFT) {
                $this->assertEquals($salesOrderThresholdTransfer, $globalThreshold);
                break;
            }
        }
    }

    /**
     * @return void
     */
    public function testCartPostSaveSalesOrderThresholdCheck(): void
    {
        $this->setupDependencies();

        // Arrange
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Act
        $this->getFacade()->addSalesOrderThresholdMessages($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckCheckoutSalesOrderThreshold(): void
    {
        $this->setupDependencies();

        // Arrange
        $quoteTransfer = $this->tester->createTestQuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $this->getFacade()->checkCheckoutSalesOrderThreshold(
            $quoteTransfer,
            $checkoutResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer
     */
    protected function createSalesOrderThresholdTransfer(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): SalesOrderThresholdTransfer {
        return (new SalesOrderThresholdTransfer())
            ->setSalesOrderThresholdValue(
                (new SalesOrderThresholdValueTransfer())
                    ->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer)
                    ->setThreshold($thresholdValue)
                    ->setFee($fee)
            )->setStore($storeTransfer)
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param string $strategyGroup
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer|null
     */
    protected function findSalesOrderThresholdTypeTransferForGroup(
        string $strategyGroup
    ): ?SalesOrderThresholdTypeTransfer {
        foreach ($this->strategies as $salesOrderThresholdStrategy) {
            if ($strategyGroup === $salesOrderThresholdStrategy->getGroup()) {
                return $salesOrderThresholdStrategy->toTransfer();
            }
        }

        return null;
    }

    /**
     * @return void
     */
    protected function setupDependencies(): void
    {
        $this->strategies = [
            new HardMinimumThresholdStrategyPlugin(),
            new SoftMinimumThresholdWithMessageStrategyPlugin(),
            new SoftMinimumThresholdWithFixedFeeStrategyPlugin(),
            new SoftMinimumThresholdWithFlexibleFeeStrategyPlugin(),
        ];

        $this->tester->setDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY, $this->strategies);

        foreach ($this->strategies as $strategy) {
            $this->tester->haveSalesOrderThresholdType($strategy->toTransfer());
        }
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface
     */
    protected function getFacade(): SalesOrderThresholdFacadeInterface
    {
        $factory = $this->createSalesOrderThresholdBusinessFactoryMock();
        return $this->createSalesOrderThresholdFacadeMock($factory);
    }
}
