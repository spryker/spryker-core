<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;

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
        $config = $this->createMinimumOrderValueConfigMock();
        $factory = $this->createMinimumOrderValueBusinessFactoryMock($config);
        $this->tester->assertMinimumOrderValueTypeTableHasRecords(count($factory->getMinimumOrderValueStrategies()));
    }

    /**
     * @return void
     */
    public function testSetStoreHardAndSoftThresholds(): void
    {
        $minimumOrderValueHardTypeTransfer = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        $minimumOrderValueSoftStrategy = $this->findMinimumOrderValueTypeTransferForGroup(
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        $storeTransferDE = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = (new CurrencyTransfer())->setIdCurrency(1)->setCode('EUR');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                100
            )
        );

        $hardThreshold2 = $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueHardTypeTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold1 = $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold2 = $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold3 = $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueSoftStrategy,
                $storeTransferUS,
                $currencyTransferUSD,
                200
            )
        );

        $this->assertEquals($hardThreshold1->getIdMinimumOrderValue(), $hardThreshold2->getIdMinimumOrderValue());
        $this->assertNotEquals($hardThreshold1->getIdMinimumOrderValue(), $softThreshold1->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMinimumOrderValue(), $softThreshold2->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMinimumOrderValue(), $softThreshold3->getIdMinimumOrderValue());
        $this->assertNotEquals($softThreshold2->getIdMinimumOrderValue(), $softThreshold3->getIdMinimumOrderValue());
    }

    /**
     * @expectedException \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return void
     */
    public function testSetStoreInvalidThresholdKeyThrowsException(): void
    {
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');
        $minimumOrderValueTypeTransferWithWrongKey = (new MinimumOrderValueTypeTransfer())->setKey('xxxx');

        $this->getFacade()->setStoreThreshold(
            $this->createMinimumOrderValueTransfer(
                $minimumOrderValueTypeTransferWithWrongKey,
                $storeTransferUS,
                $currencyTransferUSD,
                200
            )
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
            ->setMinimumOrderValueType($minimumOrderValueTypeTransfer)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setValue($thresholdValue)
            ->setFee($fee);
    }

    /**
     * @param string $strategyGroup
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer|null
     */
    protected function findMinimumOrderValueTypeTransferForGroup(
        string $strategyGroup
    ): ?MinimumOrderValueTypeTransfer {
        $config = $this->createMinimumOrderValueConfigMock();
        $factory = $this->createMinimumOrderValueBusinessFactoryMock($config);
        foreach ($factory->getMinimumOrderValueStrategies() as $minimumOrderValueStrategy) {
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
