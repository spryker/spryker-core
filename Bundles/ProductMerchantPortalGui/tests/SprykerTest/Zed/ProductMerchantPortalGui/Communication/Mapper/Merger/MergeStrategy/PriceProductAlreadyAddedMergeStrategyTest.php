<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\MergeStrategy;

use ArrayObject;
use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Mapper
 * @group Merger
 * @group MergeStrategy
 * @group PriceProductAlreadyAddedMergeStrategyTest
 * Add your own group annotations below this line
 */
class PriceProductAlreadyAddedMergeStrategyTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsApplicableReturnsTrueForMergeablePriceProducts(): void
    {
        // Arrange
        $priceProductAlreadyAddedMergeStrategy = $this->tester
            ->getFactory()
            ->createPriceProductAlreadyAddedMergeStrategy();

        $priceProductTransfers = $this->tester->createMergeablePriceProductTransfers();
        $priceProductTransfer = array_pop($priceProductTransfers);

        // Act
        $isApplicable = $priceProductAlreadyAddedMergeStrategy->isApplicable(
            $priceProductTransfer,
            new ArrayObject($priceProductTransfers),
        );

        // Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableReturnsFalseForNotMergeablePriceProducts(): void
    {
        // Arrange
        $priceProductAlreadyAddedMergeStrategy = $this->tester
            ->getFactory()
            ->createPriceProductAlreadyAddedMergeStrategy();

        $priceProductTransfers = $this->tester->createNotMergeablePriceProductTransfers();
        $priceProductTransfer = array_pop($priceProductTransfers);

        // Act
        $isApplicable = $priceProductAlreadyAddedMergeStrategy->isApplicable(
            $priceProductTransfer,
            new ArrayObject($priceProductTransfers),
        );

        // Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testMergeForMergeablePriceProducts(): void
    {
        // Arrange
        $priceProductAlreadyAddedMergeStrategy = $this->tester
            ->getFactory()
            ->createPriceProductAlreadyAddedMergeStrategy();

        $priceProductTransfers = $this->tester->createMergeablePriceProductTransfers();
        $priceProductTransfer = array_pop($priceProductTransfers);

        // Act
        $priceProductTransfers = $priceProductAlreadyAddedMergeStrategy->merge(
            $priceProductTransfer,
            new ArrayObject($priceProductTransfers),
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testMergeForNotMergeablePriceProducts(): void
    {
        // Arrange
        $priceProductAlreadyAddedMergeStrategy = $this->tester
            ->getFactory()
            ->createPriceProductAlreadyAddedMergeStrategy();

        $mergeablePriceProductTransfers = $this->tester->createMergeablePriceProductTransfers();

        $notMergeablePriceProductTransfers = $this->tester->createNotMergeablePriceProductTransfers();
        $priceProductTransfer = array_pop($notMergeablePriceProductTransfers);

        // Act
        $mergedPriceProductTransfers = $priceProductAlreadyAddedMergeStrategy->merge(
            $priceProductTransfer,
            new ArrayObject($mergeablePriceProductTransfers),
        );

        // Assert
        $this->assertCount(2, $mergedPriceProductTransfers);
    }
}
