<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\ProductConfigurationStorage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfigurationStorage\ProductConfigurationStorageService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageServiceTest
 * Add your own group annotations below this line
 */
class ProductConfigurationStorageServiceTest extends Unit
{
    protected const PRODUCT_CONFIGURATION_HASH = 'deee4568be45b9504e24c30e8de3b533';

    /**
     * @var \Spryker\Service\ProductConfigurationStorage\ProductConfigurationStorageServiceInterface
     */
    protected $productConfigurationStorageService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productConfigurationStorageService = new ProductConfigurationStorageService();
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationPricesWillReturnOnlyConfigurationInstanceRelatedPrices(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer([$configurablePriceProductTransfer]);

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer, $resultPriceProductTransfer);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationPricesWillReturnOnlyCorrectConfigurationInstancePrices(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer1 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer2 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => 'some-random-hash',
        ]);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer([$configurablePriceProductTransfer1]);

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer1, $configurablePriceProductTransfer2];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer1, $resultPriceProductTransfer);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationPricesWillReturnAllCorrectConfigurationInstancePrices(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer1 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer2 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer3 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => 'some-random-hash',
        ]);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer(
            [$configurablePriceProductTransfer1, $configurablePriceProductTransfer2]
        );

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer1, $configurablePriceProductTransfer2, $configurablePriceProductTransfer3];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(2, $priceProductTransfers);
        $resultPriceProductTransfer1 = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer1->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer1, $resultPriceProductTransfer1);
        $resultPriceProductTransfer2 = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer2->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer2, $resultPriceProductTransfer2);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationPricesWillReturnDefaultPriceIfConfigurationInstanceDoesNotHavePrices(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => 'some-random-hash',
        ]);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer([]);

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationPrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertNull($resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($priceProductTransfer, $resultPriceProductTransfer);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationVolumePricesWillReturnPriceWithoutVolumeQuantityWhenItemQuantityIsOne(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer1 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer2 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer1->setVolumeQuantity(null);
        $configurablePriceProductTransfer2->setVolumeQuantity(5);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer(
            [$configurablePriceProductTransfer1, $configurablePriceProductTransfer2]
        );

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer1, $configurablePriceProductTransfer2];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity(1)
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer1, $resultPriceProductTransfer);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationVolumePricesWillReturnPriceWithCorrectVolumeQuantity(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer1 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer2 = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);
        $configurablePriceProductTransfer1->setVolumeQuantity(5);
        $configurablePriceProductTransfer2->setVolumeQuantity(10);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer(
            [$configurablePriceProductTransfer1, $configurablePriceProductTransfer2]
        );

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer1, $configurablePriceProductTransfer2];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity(7)
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertEquals(static::PRODUCT_CONFIGURATION_HASH, $resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($configurablePriceProductTransfer1, $resultPriceProductTransfer);
    }

    /**
     * @return void
     */
    public function testFilterProductConfigurationVolumePricesWillReturnDefaultPriceIfConfigurationInstanceDoesNotHavePrices(): void
    {
        // Arrange
        $priceProductTransfer = $this->createPriceProductTransfer();
        $configurablePriceProductTransfer = $this->createPriceProductTransfer([
            PriceProductDimensionTransfer::PRODUCT_CONFIGURATION_INSTANCE_HASH => static::PRODUCT_CONFIGURATION_HASH,
        ]);

        $productConfigurationInstanceTransfer = $this->createProductConfigurationInstanceTransfer([]);

        $priceProductTransfers = [$priceProductTransfer, $configurablePriceProductTransfer];
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->productConfigurationStorageService->filterProductConfigurationVolumePrices(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $resultPriceProductTransfer = array_shift($priceProductTransfers);
        $this->assertNull($resultPriceProductTransfer->getPriceDimension()->getProductConfigurationInstanceHash());
        $this->assertEquals($priceProductTransfer, $resultPriceProductTransfer);
    }

    /**
     * @param array $priceDimensionOverrideData
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(array $priceDimensionOverrideData = []): PriceProductTransfer
    {
        return (new PriceProductBuilder())
            ->withMoneyValue()
            ->withPriceDimension($priceDimensionOverrideData)
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function createProductConfigurationInstanceTransfer(array $priceProductTransfers): ProductConfigurationInstanceTransfer
    {
        return (new ProductConfigurationInstanceTransfer())
            ->setPrices(new ArrayObject($priceProductTransfers));
    }
}
