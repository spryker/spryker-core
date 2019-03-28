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
     * @dataProvider getNearestQuantityWithMinMaxAndIntervalDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return void
     */
    public function testGetNearestQuantityWithMinMaxAndInterval(
        ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        $quantity,
        $expectedResult
    ): void {
        $productQuantityRounderMock = $this->createProductQuantityRounderMock();

        $this->assertEquals(
            $expectedResult,
            $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, $quantity)
        );
    }

    /**
     * @return array
     */
    public function getNearestQuantityWithMinMaxAndIntervalDataProvider(): array
    {
        return [
            'int quantity < min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 0, 1),
            'int quantity = min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 1, 1),
            'int quantity nearer to min then to min+interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 3, 1),
            'int quantity nearer to min+interval then to min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 4, 6),
            'int quantity > last interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 100, 96),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 200, 96),
        ];
    }

    /**
     * @param int|float $min
     * @param int|float $max
     * @param int|float $interval
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return array
     */
    protected function getDataForGetNearestQuantityWithMinMaxAndInterval($min, $max, $interval, $quantity, $expectedResult)
    {
        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer($min, $interval, $max);

        return [$productQuantityStorageTransfer, $quantity, $expectedResult];
    }

    /**
     * @dataProvider getNearestQuantityMaxDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return void
     */
    public function testGetNearestQuantityWithMax(
        ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        $quantity,
        $expectedResult
    ): void {
        $productQuantityRounderMock = $this->createProductQuantityRounderMock();

        $this->assertEquals($expectedResult, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, $quantity));
    }

    /**
     * @return array
     */
    public function getNearestQuantityMaxDataProvider(): array
    {
        return [
            'int quantity = 0' => $this->getDataForGetNearestQuantityWithMax(30, 0, 1),
            '0 < int quantity < max' => $this->getDataForGetNearestQuantityWithMax(30, 10, 10),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMax(30, 31, 30),
        ];
    }

    /**
     * @param int|float $max
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return array
     */
    protected function getDataForGetNearestQuantityWithMax($max, $quantity, $expectedResult): array
    {
        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer(null, null, $max);

        return [$productQuantityStorageTransfer, $quantity, $expectedResult];
    }

    /**
     * @dataProvider getNearestQuantityWithMinAndMaxDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return void
     */
    public function testGetNearestQuantityWithMinAndMax(
        ProductQuantityStorageTransfer $productQuantityStorageTransfer,
        $quantity,
        $expectedResult
    ): void {
        $productQuantityRounderMock = $this->createProductQuantityRounderMock();

        $this->assertEquals($expectedResult, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, $quantity));
    }

    /**
     * @return array
     */
    public function getNearestQuantityWithMinAndMaxDataProvider(): array
    {
        return [
            'int quantity < min' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 0, 5),
            'min < int quantity < max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 11, 11),
            'int quantity = max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 30, 30),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 31, 30),
        ];
    }

    /**
     * @param int|float $min
     * @param int|float $max
     * @param int|float $quantity
     * @param int|float $expectedResult
     *
     * @return array
     */
    protected function getDataForGetNearestQuantityWithMinAndMax($min, $max, $quantity, $expectedResult): array
    {
        $productQuantityStorageTransfer = $this->createProductQuantityStorageTransfer($min, null, $max);

        return [$productQuantityStorageTransfer, $quantity, $expectedResult];
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
