<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductQuantity\Rounder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Service\ProductQuantity\Rounder\ProductQuantityRounder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group ProductQuantity
 * @group Rounder
 * @group ProductQuantityRounderTest
 * Add your own group annotations below this line
 * @group ProductQuantityServiceTest
 */
class ProductQuantityRounderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetNearestQuantityShouldReturnRoundedQuantity(): void
    {
        $productQuantityRounder = new ProductQuantityRounder();

        $this->assertInstanceOf(ProductQuantityRounder::class, $productQuantityRounder);

        $productQuantityTransfer = $this->createProductQuantityTransfer(1, 5, 100);
        $this->assertEquals(1, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 0));
        $this->assertEquals(1, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 1));
        $this->assertEquals(1, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 3));
        $this->assertEquals(6, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 4));
        $this->assertEquals(96, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 100));
        $this->assertEquals(96, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 200));

        $productQuantityTransfer = $this->createProductQuantityTransfer(null, null, 30);
        $this->assertEquals(1, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 0));
        $this->assertEquals(10, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 10));
        $this->assertEquals(30, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 31));

        $productQuantityTransfer = $this->createProductQuantityTransfer(5, null, 30);
        $this->assertEquals(5, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 0));
        $this->assertEquals(11, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 11));
        $this->assertEquals(30, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 30));
        $this->assertEquals(30, $productQuantityRounder->getNearestQuantity($productQuantityTransfer, 45));
    }

    /**
     * @param int|null $min
     * @param int|null $interval
     * @param int|null $max
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function createProductQuantityTransfer(?int $min, ?int $interval, ?int $max): ProductQuantityTransfer
    {
        return (new ProductQuantityTransfer())
            ->setQuantityMin($min)
            ->setQuantityInterval($interval)
            ->setQuantityMax($max);
    }
}
