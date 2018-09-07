<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueLocalizedMessageTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\HardThresholdStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithFixedFeeStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithFlexibleFeeStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\Communication\Plugin\Strategy\SoftThresholdWithMessageStrategyPlugin;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MinimumOrderValue
 * @group Business
 * @group Facade
 * @group MinimumOrderValueFacadeTest
 * Add your own group annotations below this line
 */
class MinimumOrderValueFacadeTest extends MinimumOrderValueMocks
{
    /**
     * @var \SprykerTest\Zed\MinimumOrderValue\MinimumOrderValueBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[]
     */
    protected $strategies;

    /**
     * @return void
     */
    public function testInstallMinimumOrderValueTypesShouldPersistTypes(): void
    {
        $this->setupDependencies();

        // Action
        $this->getFacade()->installMinimumOrderValueTypes();

        // Assert
        $this->tester->assertMinimumOrderValueTypeTableHasRecords(count($this->strategies));
    }

    /**
     * @return void
     */
    public function testSaveHardAndSoftMinimumOrderValues(): void
    {
        $this->setupDependencies();

        // Prepare
        $minimumOrderValueHardTypeTransfer = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueConfig::GROUP_HARD
        );

        $minimumOrderValueSoftStrategy = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueConfig::GROUP_SOFT
        );

        $storeTransferDE = $this->tester->getStoreTransfer();
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = $this->tester->getCurrencyTransfer();
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                10000
            )
        );

        $hardThreshold2 = $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold1 = $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold2 = $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold3 = $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );

        // Assert
        $this->assertEquals($hardThreshold1->getIdMinimumOrderValue(), $hardThreshold2->getIdMinimumOrderValue());
        $this->assertNotEquals($hardThreshold1->getIdMinimumOrderValue(), $softThreshold1->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMinimumOrderValue(), $softThreshold2->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMinimumOrderValue(), $softThreshold3->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold2->getIdMinimumOrderValue(), $softThreshold3->getIdMinimumOrderValue());
    }

    /**
     * @expectedException \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return void
     */
    public function testSaveMinimumOrderValueWithInvalidKeyThrowsException(): void
    {
        $this->setupDependencies();

        // Prepare
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');
        $minimumOrderValueTypeTransferWithWrongKey = (new MinimumOrderValueTypeTransfer())->setKey('xxxx');

        // Action
        $this->getFacade()->saveMinimumOrderValue(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueTypeTransferWithWrongKey,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );
    }

    /**
     * @return void
     */
    public function testSaveMinimumOrderValueWithLocalizedMessages(): void
    {
        $this->setupDependencies();

        // Prepare
        $minimumOrderValueSoftStrategy = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueConfig::GROUP_SOFT
        );

        $storeTransfer = $this->tester->getStoreTransfer();
        $currencyTransfer = $this->tester->getCurrencyTransfer();

        $minimumOrderValueTValueTransfer = $this->createMinimumOrderValueTransfer(
            $minimumOrderValueSoftStrategy,
            $storeTransfer,
            $currencyTransfer,
            20000
        );

        $minimumOrderValueTValueTransfer
            ->addLocalizedMessage(
                (new MinimumOrderValueLocalizedMessageTransfer())
                    ->setLocaleCode('en_US')
                    ->setMessage('Test message')
            );

        // Action
        $softThreshold = $this->getFacade()->saveMinimumOrderValue(
            $minimumOrderValueTValueTransfer
        );

        // Assert
        $this->assertNotEmpty($softThreshold->getMinimumOrderValueThreshold()->getMessageGlossaryKey());
        $this->assertCount(1, $softThreshold->getLocalizedMessages());
    }

    /**
     * @return void
     */
    public function testFindMinimumOrderValues(): void
    {
        $this->setupDependencies();

        // Prepare
        $storeTransfer = $this->tester->getStoreTransfer();
        $currencyTransfer = $this->tester->getCurrencyTransfer();

        $minimumOrderValueSoftStrategy = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueConfig::GROUP_SOFT
        );

        $minimumOrderValueTValueTransfer = $this->createMinimumOrderValueTransfer(
            $minimumOrderValueSoftStrategy,
            $storeTransfer,
            $currencyTransfer,
            20000
        );

        $minimumOrderValueTValueTransfer
            ->addLocalizedMessage(
                (new MinimumOrderValueLocalizedMessageTransfer())
                    ->setLocaleCode('en_US')
                    ->setMessage('Test message')
            );

        // Action
        $globalThresholds = $this->getFacade()->findMinimumOrderValues(
            $storeTransfer,
            $currencyTransfer
        );

        // Assert
        $this->assertCount(2, $globalThresholds);
        foreach ($globalThresholds as $globalThreshold) {
            $this->assertCount(2, $globalThreshold->getLocalizedMessages());
        }
    }

    /**
     * @return void
     */
    public function testCartPostSaveMinimumOrderValueCheck(): void
    {
        $this->setupDependencies();

        // Prepare
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Action
        $this->getFacade()->addMinimumOrderValueMessages($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckCheckoutMinimumOrderValue(): void
    {
        $this->setupDependencies();

        // Prepare
        $quoteTransfer = $this->tester->createTestQuoteTransfer();
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Action
        $this->getFacade()->checkCheckoutMinimumOrderValue(
            $quoteTransfer,
            $checkoutResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    protected function createMinimumOrderValueTransfer(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MinimumOrderValueTransfer {
        return (new MinimumOrderValueTransfer())
            ->setMinimumOrderValueThreshold(
                (new MinimumOrderValueThresholdTransfer())
                    ->setMinimumOrderValueType($minimumOrderValueTypeTransfer)
                    ->setThreshold($thresholdValue)
                    ->setFee($fee)
            )->setStore($storeTransfer)
            ->setCurrency($currencyTransfer);
    }

    /**
     * @param string $strategyGroup
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer|null
     */
    protected function findMinimumOrderValueTypeTransferForGroup(
        string $strategyGroup
    ): ?MinimumOrderValueTypeTransfer {
        foreach ($this->strategies as $minimumOrderValueStrategy) {
            if ($strategyGroup === $minimumOrderValueStrategy->getGroup()) {
                return $minimumOrderValueStrategy->toTransfer();
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
            new HardThresholdStrategyPlugin(),
            new SoftThresholdWithMessageStrategyPlugin(),
            new SoftThresholdWithFixedFeeStrategyPlugin(),
            new SoftThresholdWithFlexibleFeeStrategyPlugin(),
        ];

        $this->tester->setDependency(MinimumOrderValueDependencyProvider::PLUGINS_MINIMUM_ORDER_VALUE_STRATEGY, $this->strategies);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        $factory = $this->createMinimumOrderValueBusinessFactoryMock();
        return $this->createMinimumOrderValueFacadeMock($factory);
    }
}
