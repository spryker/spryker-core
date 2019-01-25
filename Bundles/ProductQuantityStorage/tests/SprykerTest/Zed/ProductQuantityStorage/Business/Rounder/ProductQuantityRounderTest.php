<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityStorage\Business\Rounder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductQuantityStorage
 * @group Business
 * @group Rounder
 * @group ProductQuantityRounderTest
 * Add your own group annotations below this line
 */
class ProductQuantityRounderTest extends Unit
{
    /**
     * @return void
     */
    public function testGetNearestQuantity(): void
    {
        $productQuantityRounderMock = $this->createProductQuantityRounderMock();

        $this->assertInstanceOf(ProductQuantityRounder::class, $productQuantityRounderMock);

        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer(1, 5, 100);
        $this->assertEquals(1, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 0));
        $this->assertEquals(1, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 1));
        $this->assertEquals(1, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 3));
        $this->assertEquals(6, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 4));
        $this->assertEquals(96, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 100));
        $this->assertEquals(96, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 200));

        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer(null, null, 30);
        $this->assertEquals(1, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 0));
        $this->assertEquals(10, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 10));
        $this->assertEquals(30, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 31));

        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer(5, null, 30);
        $this->assertEquals(5, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 0));
        $this->assertEquals(11, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 11));
        $this->assertEquals(30, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 30));
        $this->assertEquals(30, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, 45));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockBuilder
     */
    protected function createProductQuantityRounderMock(): MockObject
    {
        return $this->getMockBuilder(ProductQuantityRounder::class)->setMethods(null)->getMock();
    }

    /**
     * @param int|null $min
     * @param int|null $interval
     * @param int|null $max
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function createProductQuantityStorageTransfer(?int $min, ?int $interval, ?int $max): ProductQuantityStorageTransfer
    {
        return (new ProductQuantityStorageTransfer())
            ->setQuantityMin($min)
            ->setQuantityInterval($interval)
            ->setQuantityMax($max);
    }
}
