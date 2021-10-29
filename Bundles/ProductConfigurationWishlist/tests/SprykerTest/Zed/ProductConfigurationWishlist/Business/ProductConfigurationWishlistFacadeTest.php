<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationWishlist\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductConfigurationInstanceBuilder;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationWishlist
 * @group Business
 * @group Facade
 * @group ProductConfigurationWishlistFacadeTest
 * Add your own group annotations below this line
 */
class ProductConfigurationWishlistFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_PRODUCT_CONFIGURATION_INSTANCE_DATA = 'FAKE_PRODUCT_CONFIGURATION_INSTANCE_DATA';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationWishlist\ProductConfigurationWishlistBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlistTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected $wishlistItemTransfer;

    /**
     * @return void
     */
    public function testExpandWishlistItemWithProductConfigurationDataWithProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $wishlistItemTransfer = (new WishlistItemTransfer())->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        // Act
        $wishlistItemTransfer = $this->tester->getFacade()->expandWishlistItemWithProductConfigurationData($wishlistItemTransfer);

        // Assert
        $this->assertNotNull($wishlistItemTransfer->getProductConfigurationInstanceData());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemWithProductConfigurationDataWithoutProductConfiguration(): void
    {
        // Arrange
        $wishlistItemTransfer = new WishlistItemTransfer();

        // Act
        $wishlistItemTransfer = $this->tester->getFacade()->expandWishlistItemWithProductConfigurationData($wishlistItemTransfer);

        // Assert
        $this->assertNull($wishlistItemTransfer->getProductConfigurationInstanceData());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemWithProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $wishlistItemTransfer = (new WishlistItemTransfer())->setProductConfigurationInstanceData(
            $this->tester->encodeJson($productConfigurationInstanceTransfer->toArray()),
        );

        // Act
        $wishlistItemTransfer = $this->tester->getFacade()->expandWishlistItemWithProductConfiguration($wishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $wishlistItemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemWithoutProductConfiguration(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer());

        // Act
        $wishlistItemTransfer = $this->tester->getFacade()->expandWishlistItemWithProductConfiguration($wishlistItemTransfer);

        // Assert
        $this->assertNull($wishlistItemTransfer->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testCheckWishlistItemProductConfigurationExists(): void
    {
        // Arrange
        $this->setUpData();
        $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ],
        );

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkWishlistItemProductConfiguration($this->wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckWishlistItemProductConfigurationNotExists(): void
    {
        // Arrange
        $this->setUpData();

        $this->wishlistItemTransfer->setProductConfigurationInstance(
            new ProductConfigurationInstanceTransfer(),
        );

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->tester->getFacade()->checkWishlistItemProductConfiguration($this->wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductConfigurationExists(): void
    {
        // Arrange
        $this->setUpData();
        $this->tester->haveProductConfiguration(
            [
                ProductConfigurationTransfer::FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ],
        );

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()->checkUpdateWishlistItemProductConfiguration($this->wishlistItemTransfer);

        // Assert
        $this->assertTrue($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckUpdateWishlistItemProductConfigurationNotExists(): void
    {
        // Arrange
        $this->setUpData();

        $this->wishlistItemTransfer->setProductConfigurationInstance(
            new ProductConfigurationInstanceTransfer(),
        );

        // Act
        $wishlistPreUpdateItemCheckResponseTransfer = $this->tester->getFacade()->checkUpdateWishlistItemProductConfiguration($this->wishlistItemTransfer);

        // Assert
        $this->assertFalse($wishlistPreUpdateItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWithProductConfigurationWillNotExpandEmptyCollection(): void
    {
        // Arrange
        $wishlistTransfer = (new WishlistTransfer());

        // Act
        $wishlistTransfer = $this->tester->getFacade()->expandWishlistItemCollectionWithProductConfiguration($wishlistTransfer);

        // Assert
        $this->assertSame(0, $wishlistTransfer->getWishlistItems()->count());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWithProductConfigurationWillNotExpandItemsWithoutProductConfiguration(): void
    {
        // Arrange
        $this->setUpData([
            WishlistItemTransfer::PRODUCT_CONFIGURATION_INSTANCE_DATA => null,
        ]);
        $this->wishlistTransfer->setWishlistItems((new ArrayObject([$this->wishlistItemTransfer])));

        // Act
        $wishlistTransfer = $this->tester->getFacade()->expandWishlistItemCollectionWithProductConfiguration($this->wishlistTransfer);

        // Assert
        $this->assertSame(1, $wishlistTransfer->getWishlistItems()->count());
        $this->assertNull($wishlistTransfer->getWishlistItems()[0]->getProductConfigurationInstance());
    }

    /**
     * @return void
     */
    public function testExpandWishlistItemCollectionWithProductConfigurationWillExpandItemsWithProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceBuilder())->build();
        $this->setUpData([
            WishlistItemTransfer::PRODUCT_CONFIGURATION_INSTANCE_DATA => $this->tester->encodeJson($productConfigurationInstanceTransfer->toArray()),
        ]);
        $this->wishlistTransfer->setWishlistItems((new ArrayObject([$this->wishlistItemTransfer])));

        // Act
        $wishlistTransfer = $this->tester->getFacade()->expandWishlistItemCollectionWithProductConfiguration($this->wishlistTransfer);

        // Assert
        $this->assertSame(1, $wishlistTransfer->getWishlistItems()->count());

        $wishlistProductConfigurationInstance = $wishlistTransfer->getWishlistItems()[0]->getProductConfigurationInstance();
        $this->assertInstanceOf(ProductConfigurationInstanceTransfer::class, $wishlistProductConfigurationInstance);
    }

    /**
     * @return void
     */
    public function testHasConfigurableProductItemsWillReturnFalseWhenWishlistDoesNotHaveItems(): void
    {
        // Arrange
        $wishlistTransfer = (new WishlistTransfer())
            ->addWishlistItem((new WishlistItemTransfer())->setProductConfigurationInstance(null))
            ->addWishlistItem((new WishlistItemTransfer())->setProductConfigurationInstance(null))
            ->addWishlistItem((new WishlistItemTransfer())->setProductConfigurationInstance(null));

        // Act
        $hasConfigurableProductItems = $this->tester->getFacade()->hasConfigurableProductItems($wishlistTransfer);

        // Assert
        $this->assertFalse($hasConfigurableProductItems);
    }

    /**
     * @return void
     */
    public function testHasConfigurableProductItemsWillReturnFalseWhenItemsDoNotHaveProductConfigurationInstanceData(): void
    {
        // Arrange
        $this->setUpData([
            WishlistItemTransfer::PRODUCT_CONFIGURATION_INSTANCE_DATA => null,
        ]);
        $this->wishlistTransfer->setWishlistItems((new ArrayObject([$this->wishlistItemTransfer])));

        // Act
        $hasConfigurableProductItems = $this->tester->getFacade()->hasConfigurableProductItems($this->wishlistTransfer);

        // Assert
        $this->assertFalse($hasConfigurableProductItems);
    }

    /**
     * @return void
     */
    public function testHasConfigurableProductItemsWillReturnTrueWhenItemsHaveProductConfigurationInstanceData(): void
    {
        // Arrange
        $this->setUpData([
            WishlistItemTransfer::PRODUCT_CONFIGURATION_INSTANCE_DATA => static::FAKE_PRODUCT_CONFIGURATION_INSTANCE_DATA,
        ]);

        $this->wishlistTransfer->addWishlistItem(
            $this->wishlistItemTransfer,
        );

        // Act
        $hasConfigurableProductItems = $this->tester->getFacade()->hasConfigurableProductItems($this->wishlistTransfer);

        // Assert
        $this->assertTrue($hasConfigurableProductItems);
    }

    /**
     * @param array $wishlistItemData
     *
     * @return void
     */
    protected function setUpData(array $wishlistItemData = []): void
    {
        $this->productConcreteTransfer = $this->tester->haveProduct();
        $this->customerTransfer = $this->tester->haveCustomer();
        $this->wishlistTransfer = $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer()]);
        $this->wishlistItemTransfer = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->productConcreteTransfer->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlistTransfer->getName(),
        ] + $wishlistItemData);
    }
}
