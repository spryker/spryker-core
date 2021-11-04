<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationWishlist;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationWishlist
 * @group ExpandPriceProductsWithProductConfigurationPricesTest
 * Add your own group annotations below this line
 */
class ExpandPriceProductsWithProductConfigurationPricesTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationWishlist\ProductConfigurationWishlistClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandPriceProductsWithProductConfigurationPricesWithoutProductConfigurationInstance(): void
    {
        // Arrange
        $productViewTransfer = (new ProductViewTransfer())
            ->setProductConfigurationInstance(null);

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->expandPriceProductsWithProductConfigurationPrices([], $productViewTransfer);

        // Assert
        $this->assertEmpty($priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testExpandPriceProductsWithProductConfigurationPricesWithoutProductConfigurationPrices(): void
    {
        // Arrange
        $productViewTransfer = (new ProductViewTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())->setPrices(new ArrayObject()),
            );

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->expandPriceProductsWithProductConfigurationPrices([], $productViewTransfer);

        // Assert
        $this->assertEmpty($priceProductTransfers);
    }

    /**
     * @return void
     */
    public function testExpandPriceProductsWithProductConfigurationPricesWithProductConfigurationPrices(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setPrices(new ArrayObject([
                new PriceProductTransfer(),
                new PriceProductTransfer(),
                new PriceProductTransfer(),
            ]));

        $productViewTransfer = (new ProductViewTransfer())
            ->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->expandPriceProductsWithProductConfigurationPrices([], $productViewTransfer);

        // Assert
        $this->assertCount(
            $productViewTransfer->getProductConfigurationInstanceOrFail()->getPrices()->count(),
            $priceProductTransfers,
        );
    }

    /**
     * @return void
     */
    public function testExpandPriceProductsWithProductConfigurationPricesWithAlreadyProvidedPrices(): void
    {
        // Arrange
        $productViewTransfer = (new ProductViewTransfer())
            ->setProductConfigurationInstance(
                (new ProductConfigurationInstanceTransfer())
                    ->setPrices(new ArrayObject([
                        new PriceProductTransfer(),
                    ])),
            );

        $priceProductTransfers = [
            new PriceProductTransfer(),
            new PriceProductTransfer(),
        ];

        // Act
        $priceProductTransfers = $this->tester
            ->getClient()
            ->expandPriceProductsWithProductConfigurationPrices($priceProductTransfers, $productViewTransfer);

        // Assert
        $this->assertCount(3, $priceProductTransfers);
    }
}
