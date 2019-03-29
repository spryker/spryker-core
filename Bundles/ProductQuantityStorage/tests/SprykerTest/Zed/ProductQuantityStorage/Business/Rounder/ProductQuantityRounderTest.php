<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductQuantityStorage\Business\Rounder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToUtilQuantityServiceBridge;
use Spryker\Client\ProductQuantityStorage\ProductQuantityStorageConfig;
use Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounder;
use Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounderInterface;

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
     * @var \SprykerTest\Zed\ProductQuantityStorage\ProductQuantityStorageBusinessTester
     */
    protected $tester;

    /**
     * @group her
     *
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
        $productQuantityRounderMock = $this->createProductQuantityRounder();

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
            'float quantity < min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.1, 0, 0.5),
            'int quantity = min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 1, 1),
            'float quantity = min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.1, 0.5, 0.5),
            'int quantity nearer to min then to min+interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 3, 1),
            'float quantity nearer to min then to min+interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.3, 0.6, 0.5),
            'int quantity nearer to min+interval then to min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 4, 6),
            'float quantity nearer to min+interval then to min' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.3, 0.7, 0.8),
            'int quantity > last interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 100, 96),
            'float quantity > last interval' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.3, 1.8, 1.7),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(1, 100, 5, 200, 96),
            'float quantity > max' => $this->getDataForGetNearestQuantityWithMinMaxAndInterval(0.5, 1.8, 0.3, 2.5, 1.7),
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
        $productQuantityRounderMock = $this->createProductQuantityRounder();

        $this->assertEquals($expectedResult, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, $quantity));
    }

    /**
     * @return array
     */
    public function getNearestQuantityMaxDataProvider(): array
    {
        return [
            'int quantity = 0' => $this->getDataForGetNearestQuantityWithMax(30, 0, 1),
            'float quantity = 0' => $this->getDataForGetNearestQuantityWithMax(1.8, 0, 1),
            '0 < int quantity < max' => $this->getDataForGetNearestQuantityWithMax(30, 10, 10),
            '0 < float quantity < max' => $this->getDataForGetNearestQuantityWithMax(1.8, 1.3, 1.3),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMax(30, 31, 30),
            'float quantity > max' => $this->getDataForGetNearestQuantityWithMax(1.8, 2.5, 1.8),
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
        $productQuantityRounderMock = $this->createProductQuantityRounder();

        $this->assertEquals($expectedResult, $productQuantityRounderMock->getNearestQuantity($productQuantityStorageTransfer, $quantity));
    }

    /**
     * @return array
     */
    public function getNearestQuantityWithMinAndMaxDataProvider(): array
    {
        return [
            'int quantity < min' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 0, 5),
            'float quantity < min' => $this->getDataForGetNearestQuantityWithMinAndMax(0.3, 1.8, 0.1, 0.3),
            'min < int quantity < max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 11, 11),
            'min < float quantity < max' => $this->getDataForGetNearestQuantityWithMinAndMax(0.3, 1.8, 1.2, 1.2),
            'int quantity = max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 30, 30),
            'float quantity = max' => $this->getDataForGetNearestQuantityWithMinAndMax(0.3, 1.8, 1.8, 1.8),
            'int quantity > max' => $this->getDataForGetNearestQuantityWithMinAndMax(5, 30, 31, 30),
            'float quantity > max' => $this->getDataForGetNearestQuantityWithMinAndMax(0.3, 1.8, 2.1, 1.8),
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
     * @return \Spryker\Client\ProductQuantityStorage\Rounder\ProductQuantityRounderInterface
     */
    protected function createProductQuantityRounder(): ProductQuantityRounderInterface
    {
        $utilQuantityService = new ProductQuantityStorageToUtilQuantityServiceBridge(
            $this->tester->getLocator()->utilQuantity()->service()
        );

        return new ProductQuantityRounder(new ProductQuantityStorageConfig(), $utilQuantityService);
    }

    /**
     * @param int|float|null $min
     * @param int|float|null $interval
     * @param int|float|null $max
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function createProductQuantityStorageTransfer($min, $interval, $max): ProductQuantityStorageTransfer
    {
        return (new ProductQuantityStorageTransfer())
            ->setQuantityMin($min)
            ->setQuantityInterval($interval)
            ->setQuantityMax($max);
    }
}
