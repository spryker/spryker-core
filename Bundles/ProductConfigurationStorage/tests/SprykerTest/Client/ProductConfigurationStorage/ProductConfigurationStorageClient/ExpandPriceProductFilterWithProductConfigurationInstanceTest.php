<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationStorage
 * @group ProductConfigurationStorageClient
 * @group ExpandPriceProductFilterWithProductConfigurationInstanceTest
 * Add your own group annotations below this line
 */
class ExpandPriceProductFilterWithProductConfigurationInstanceTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationStorage\ProductConfigurationStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPriceProductFilterWithProductConfigurationInstance(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder([
            ProductConfigurationInstanceTransfer::PRICES => new PriceProductTransfer(),
        ]))->build();
        $productViewTransfer = (new ProductViewTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductFilterTransfer = $this->tester
            ->getClient()
            ->expandPriceProductFilterWithProductConfigurationInstance($productViewTransfer, new PriceProductFilterTransfer());

        // Assert
        $this->assertNotNull($priceProductFilterTransfer->getProductConfigurationInstance());
        $this->assertEquals($productConfigurationInstanceTransfer, $priceProductFilterTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandPriceProductFilterWithProductConfigurationInstanceWithoutInstance(): void
    {
        // Arrange

        // Act
        $priceProductFilterTransfer = $this->tester
            ->getClient()
            ->expandPriceProductFilterWithProductConfigurationInstance(new ProductViewTransfer(), new PriceProductFilterTransfer());

        // Assert
        $this->assertNull($priceProductFilterTransfer->getProductConfigurationInstance());
    }
}
