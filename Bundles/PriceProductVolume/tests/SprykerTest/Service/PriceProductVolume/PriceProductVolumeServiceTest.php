<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\PriceProductVolume;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\PriceProductVolume\PriceProductVolumeService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group PriceProductVolume
 * @group PriceProductVolumeServiceTest
 *
 * Add your own group annotations below this line
 */
class PriceProductVolumeServiceTest extends Unit
{
    /**
     * @var int
     */
    protected const VOLUME_QUANTITY = 5;
    /**
     * @var int
     */
    protected const GROSS_AMOUNT = 200;
    /**
     * @var int
     */
    protected const NET_AMOUNT = 123;

    /**
     * @var \SprykerTest\Service\PriceProductVolume\PriceProductVolumeServiceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Service\PriceProductVolume\PriceProductVolumeService
     */
    protected $priceProductVolumeService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductVolumeService = new PriceProductVolumeService();
    }

    /**
     * @return void
     */
    public function testAddVolumePriceAddsVolumePrice(): void
    {
        // Arrange
        $quantity = static::VOLUME_QUANTITY;
        $grossAmount = static::GROSS_AMOUNT;
        $netAmount = static::NET_AMOUNT;
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $newVolumePriceProductTransfer = $this->tester->createVolumePriceProductTransfer($quantity, $grossAmount, $netAmount);
        $expectedJson = sprintf(
            '{"volume_prices":[{"quantity":1,"net_price":110,"gross_price":120},{"quantity":%d,"net_price":%d,"gross_price":%d},{"quantity":100,"net_price":80,"gross_price":100}]}',
            $quantity,
            $netAmount,
            $grossAmount
        );

        // Act
        $basePriceProductTransfer = $this->priceProductVolumeService->addVolumePrice(
            $basePriceProductTransfer,
            $newVolumePriceProductTransfer
        );

        // Assert
        $this->assertSame($expectedJson, $basePriceProductTransfer->getMoneyValueOrFail()->getPriceData());
    }

    /**
     * @return void
     */
    public function testDeleteVolumePriceDeletesVolumePrice(): void
    {
        // Arrange
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $volumePriceProductTransferToDelete = $this->tester->createVolumePriceProductTransfer(1);
        $expectedJson = '{"volume_prices":[{"quantity":100,"net_price":80,"gross_price":100}]}';

        // Act
        $basePriceProductTransfer = $this->priceProductVolumeService->deleteVolumePrice(
            $basePriceProductTransfer,
            $volumePriceProductTransferToDelete
        );

        // Assert
        $this->assertSame($expectedJson, $basePriceProductTransfer->getMoneyValueOrFail()->getPriceData());
    }

    /**
     * @return void
     */
    public function testReplaceVolumePriceReplacesVolumePrice(): void
    {
        // Arrange
        $quantity = static::VOLUME_QUANTITY;
        $grossAmount = static::GROSS_AMOUNT;
        $netAmount = static::NET_AMOUNT;
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $volumePriceProductTransferToReplace = $this->tester->createVolumePriceProductTransfer(1);
        $newVolumePriceProductTransfer = $this->tester->createVolumePriceProductTransfer($quantity, $grossAmount, $netAmount);
        $expectedJson = sprintf(
            '{"volume_prices":[{"quantity":%d,"net_price":%d,"gross_price":%d},{"quantity":100,"net_price":80,"gross_price":100}]}',
            $quantity,
            $netAmount,
            $grossAmount
        );

        // Act
        $basePriceProductTransfer = $this->priceProductVolumeService->replaceVolumePrice(
            $basePriceProductTransfer,
            $volumePriceProductTransferToReplace,
            $newVolumePriceProductTransfer
        );

        // Assert
        $this->assertSame($expectedJson, $basePriceProductTransfer->getMoneyValueOrFail()->getPriceData());
    }

    /**
     * @return void
     */
    public function testReplaceVolumePriceAddsVolumePriceWithNonExistingQuantity(): void
    {
        // Arrange
        $quantity = static::VOLUME_QUANTITY;
        $grossAmount = static::GROSS_AMOUNT;
        $netAmount = static::NET_AMOUNT;
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $volumePriceProductTransferToReplace = $this->tester->createVolumePriceProductTransfer(999);
        $newVolumePriceProductTransfer = $this->tester->createVolumePriceProductTransfer($quantity, $grossAmount, $netAmount);
        $expectedJson = sprintf(
            '{"volume_prices":[{"quantity":1,"net_price":110,"gross_price":120},{"quantity":%d,"net_price":%d,"gross_price":%d},{"quantity":100,"net_price":80,"gross_price":100}]}',
            $quantity,
            $netAmount,
            $grossAmount
        );

        // Act
        $basePriceProductTransfer = $this->priceProductVolumeService->replaceVolumePrice(
            $basePriceProductTransfer,
            $volumePriceProductTransferToReplace,
            $newVolumePriceProductTransfer
        );

        // Assert
        $this->assertSame($expectedJson, $basePriceProductTransfer->getMoneyValueOrFail()->getPriceData());
    }

    /**
     * @return void
     */
    public function testHasVolumePricesReturnsTrue(): void
    {
        // Arrange
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();

        // Act
        $hasVolumePrices = $this->priceProductVolumeService->hasVolumePrices($basePriceProductTransfer);

        // Assert
        $this->assertTrue($hasVolumePrices);
    }

    /**
     * @return void
     */
    public function testHasVolumePricesReturnsFalse(): void
    {
        // Arrange
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $basePriceProductTransfer
            ->getMoneyValueOrFail()
            ->setPriceData('{"volume_prices":[]}');

        // Act
        $hasVolumePrices = $this->priceProductVolumeService->hasVolumePrices($basePriceProductTransfer);

        // Assert
        $this->assertFalse($hasVolumePrices);
    }

    /**
     * @return void
     */
    public function testExtractVolumePriceReturnsVolumePriceProductTransfer(): void
    {
        // Arrange
        $basePriceProductTransfer = $this->tester->createBasePriceProductTransfer();
        $volumePriceProductTransfer = (new PriceProductTransfer())
            ->setVolumeQuantity(100);

        // Act
        $volumePriceProductTransfer = $this->priceProductVolumeService
            ->extractVolumePrice($basePriceProductTransfer, $volumePriceProductTransfer);

        // Assert
        $this->assertSame($volumePriceProductTransfer->getMoneyValueOrFail()->getNetAmountOrFail(), 80);
        $this->assertSame($volumePriceProductTransfer->getMoneyValueOrFail()->getGrossAmountOrFail(), 100);
    }
}
