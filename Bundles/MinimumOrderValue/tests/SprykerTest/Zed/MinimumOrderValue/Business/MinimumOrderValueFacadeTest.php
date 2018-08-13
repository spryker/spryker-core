<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\MinimumOrderValueStrategyInterface;

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
     * @return void
     */
    public function testInstallMinimumOrderValueTypesShouldPersistTypes(): void
    {
        // Prepare
        $this->tester->truncateMinimumOrderValues();
        $this->tester->truncateMinimumOrderValueTypes();
        $this->tester->assertMinimumOrderValueTableIsEmtpy();
        $this->tester->assertMinimumOrderValueTypeTableIsEmtpy();

        // Action
        $this->getFacade()->installMinimumOrderValueTypes();

        // Assert
        $config = $this->createMinimumOrderValueConfig();
        $this->tester->assertMinimumOrderValueTypeTableHasRecords(count($config->getMinimumOrderValueStrategies()));
    }

    /**
     * @depends testInstallMinimumOrderValueTypesShouldPersistTypes
     *
     * @return void
     */
    public function testSetStoreHardAndSoftThresholds(): void
    {
        // Prepare
        $minimumOrderValueHardTypeTransfer = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        $minimumOrderValueSoftStrategy = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        $storeTransferDE = $this->tester->createStoreTransfer();
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = $this->tester->createCurrencyTransfer();
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                100
            )
        );

        $hardThreshold2 = $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold1 = $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold2 = $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold3 = $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferUSD,
                200
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
     * @expectedException \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\StrategyNotFoundException
     *
     * @return void
     */
    public function testSetStoreInvalidThresholdKeyThrowsException(): void
    {
        // Prepare
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');
        $minimumOrderValueTypeTransferWithWrongKey = (new MinimumOrderValueTypeTransfer())->setKey('xxxx');

        // Action
        $this->getFacade()->setGlobalThreshold(
            $this->createGlobalMinimumOrderValueTransfer(
                $minimumOrderValueTypeTransferWithWrongKey,
                $storeTransferUS,
                $currencyTransferUSD,
                200
            )
        );
    }

    /**
     * @depends testSetStoreHardAndSoftThresholds
     *
     * @return void
     */
    public function testGetGlobalThresholdsByStoreAndCurrency(): void
    {
        // Prepare
        $storeTransfer = $this->tester->createStoreTransfer();
        $currencyTransfer = $this->tester->createCurrencyTransfer();

        // Action
        $this->getFacade()->getGlobalThresholdsByStoreAndCurrency(
            $storeTransfer,
            $currencyTransfer
        );
    }

    /**
     * @return void
     */
    public function testCartPostSaveMinimumOrderValueCheck(): void
    {
        // Prepare
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Action
        $this->getFacade()->cartMinimumOrderValuePostSave($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCheckCheckoutMinimumOrderValue(): void
    {
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
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    protected function createGlobalMinimumOrderValueTransfer(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): GlobalMinimumOrderValueTransfer {
        return (new GlobalMinimumOrderValueTransfer())
            ->setMinimumOrderValue(
                (new MinimumOrderValueTransfer())
                    ->setMinimumOrderValueType($minimumOrderValueTypeTransfer)
                    ->setValue($thresholdValue)
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
        $config = $this->createMinimumOrderValueConfig();
        foreach ($config->getMinimumOrderValueStrategies() as $minimumOrderValueStrategy) {
            if ($strategyGroup === $minimumOrderValueStrategy->getGroup()) {
                return $minimumOrderValueStrategy->toTransfer();
            }
        }

        return null;
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
