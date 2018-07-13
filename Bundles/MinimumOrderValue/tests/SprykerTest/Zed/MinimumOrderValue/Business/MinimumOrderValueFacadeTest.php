<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
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
        $this->tester->truncateMinimumOrderValueTypes();
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
        $minimumOrderValueHardTypeTransfer = $this->getMinimumOrderValueTypeTransferForStrategy(
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        $minimumOrderValueSoftTypeTransfer = $this->getMinimumOrderValueTypeTransferForStrategy(
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        $storeTransfer = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $currencyTransfer = (new CurrencyTransfer())->setIdCurrency(1)->setCode('EUR');

        // Action
        $hardThreshold1 = $this->getFacade()->setStoreHardThreshold(
            $minimumOrderValueHardTypeTransfer,
            $storeTransfer,
            $currencyTransfer,
            100
        );

        $hardThreshold2 = $this->getFacade()->setStoreHardThreshold(
            $minimumOrderValueHardTypeTransfer,
            $storeTransfer,
            $currencyTransfer,
            200
        );

        $softThreshold1 = $this->getFacade()->setStoreSoftThreshold(
            $minimumOrderValueSoftTypeTransfer,
            $storeTransfer,
            $currencyTransfer,
            200
        );

        $this->assertEquals($hardThreshold1->getIdMinimumOrderValue(), $hardThreshold2->getIdMinimumOrderValue());
        $this->assertNotEquals($hardThreshold1->getIdMinimumOrderValue(), $softThreshold1->getIdMinimumOrderValue());
    }

    /**
     * @expectedException \Spryker\Zed\MinimumOrderValue\Business\Exception\MinimumOrderValueStrategyGroupMismatchException
     *
     * @return void
     */
    public function testSetStoreHardThresholdWithSoftStrategyThrowsAnException(): void
    {
        $minimumOrderValueSoftTypeTransfer = $this->getMinimumOrderValueTypeTransferForStrategy(
            MinimumOrderValueStrategyInterface::GROUP_SOFT
        );

        $storeTransfer = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $currencyTransfer = (new CurrencyTransfer())->setIdCurrency(1)->setCode('EUR');

        // Action
        $this->getFacade()->setStoreHardThreshold(
            $minimumOrderValueSoftTypeTransfer,
            $storeTransfer,
            $currencyTransfer,
            200
        );
    }

    /**
     * @expectedException \Spryker\Zed\MinimumOrderValue\Business\Exception\MinimumOrderValueStrategyGroupMismatchException
     *
     * @return void
     */
    public function testSetStoreSoftThresholdWithHardStrategyThrowsAnException(): void
    {
        $minimumOrderValueHardTypeTransfer = $this->getMinimumOrderValueTypeTransferForStrategy(
            MinimumOrderValueStrategyInterface::GROUP_HARD
        );

        $storeTransfer = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $currencyTransfer = (new CurrencyTransfer())->setIdCurrency(1)->setCode('EUR');

        // Action
        $this->getFacade()->setStoreSoftThreshold(
            $minimumOrderValueHardTypeTransfer,
            $storeTransfer,
            $currencyTransfer,
            100
        );
    }

    /**
     * @param string $strategyGroup
     *
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface|null
     */
    protected function getMinimumOrderValueTypeTransferForStrategy(string $strategyGroup): MinimumOrderValueStrategyInterface
    {
        $config = $this->createMinimumOrderValueConfigMock();
        $factory = $this->createMinimumOrderValueBusinessFactoryMock($config);
        foreach ($factory->getMinimumOrderValueStrategies() as $minimumOrderValueStrategy) {
            if ($strategyGroup === $minimumOrderValueStrategy->getGroup()) {
                return $minimumOrderValueStrategy;
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
