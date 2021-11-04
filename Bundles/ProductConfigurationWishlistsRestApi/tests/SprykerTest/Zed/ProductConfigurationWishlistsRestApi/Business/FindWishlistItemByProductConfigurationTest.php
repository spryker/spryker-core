<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlistsRestApi\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationWishlistsRestApi
 * @group Business
 * @group FindWishlistItemByProductConfigurationTest
 * Add your own group annotations below this line
 */
class FindWishlistItemByProductConfigurationTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'FAKE_SKU_1';

    /**
     * @var string
     */
    protected const FAKE_SKU_2 = 'FAKE_SKU_2';

    /**
     * @var string
     */
    protected const FAKE_SKU_3 = 'FAKE_SKU_3';

    /**
     * @var string
     */
    protected const FAKE_INSTANCE_HASH = 'FAKE_INSTANCE_HASH';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationWishlistsRestApi\ProductConfigurationWishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindWishlistItemByProductConfigurationFindsCorrectWishlistItemWithConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->setDisplayData('{}');
        $productConfigurationInstanceHash = $this->tester->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_2, $productConfigurationInstanceHash));

        $wishlistItemTransfers = new ArrayObject([
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_1),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_2),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_3),
        ]);

        // Act
        $wishlistItemTransfer = $this->tester
            ->getFacade()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);

        // Assert
        $this->assertSame(static::FAKE_SKU_2, $wishlistItemTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testFindWishlistItemByProductConfigurationWithWrongProductConfigurations(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->setDisplayData('{}');

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_2, static::FAKE_INSTANCE_HASH));

        $wishlistItemTransfers = new ArrayObject([
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_1),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_2),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_3),
        ]);

        // Act
        $wishlistItemTransfer = $this->tester
            ->getFacade()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);

        // Assert
        $this->assertNull($wishlistItemTransfer);
    }

    /**
     * @return void
     */
    public function testFindWishlistItemByProductConfigurationWithUndefinedSku(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->setDisplayData('{}');
        $productConfigurationInstanceHash = $this->tester->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_2, $productConfigurationInstanceHash));

        $wishlistItemTransfers = new ArrayObject([
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_1),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_3),
        ]);

        // Act
        $wishlistItemTransfer = $this->tester
            ->getFacade()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);

        // Assert
        $this->assertNull($wishlistItemTransfer);
    }

    /**
     * @return void
     */
    public function testFindWishlistItemByProductConfigurationExpectsSkuToBeProvided(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->setDisplayData('{}');
        $productConfigurationInstanceHash = $this->tester->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuid(sprintf('%s_%s', static::FAKE_SKU_2, $productConfigurationInstanceHash));

        $wishlistItemTransfers = new ArrayObject([
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_2),
            (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer)->setSku(static::FAKE_SKU_3),
        ]);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->findWishlistItemByProductConfiguration($wishlistItemRequestTransfer, $wishlistItemTransfers);
    }
}
