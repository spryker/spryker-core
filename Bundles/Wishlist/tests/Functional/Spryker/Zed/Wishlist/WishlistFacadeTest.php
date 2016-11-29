<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Wishlist;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Wishlist\Business\WishlistFacade;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Wishlist
 * @group WishlistFacadeTest
 */
class WishlistFacadeTest extends Test
{

    const DEFAULT_NAME = 'default';

    /**
     * @var \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $product_1;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $product_2;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected $product_3;

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected $productAbstract;

    /**
     * @var \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected $customer;

    /**
     * @var \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    protected $wishlist;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->wishlistQueryContainer = new WishlistQueryContainer();
        $this->wishlistFacade = new WishlistFacade();

        $this->setupCustomer();
        $this->setupProduct();
        $this->setupWishlist();
    }

    /**
     * @return void
     */
    protected function setupCustomer()
    {
        $customerEntity = (new SpyCustomer())
            ->setCustomerReference('customer_reference')
            ->setEmail('email');

        $customerEntity->save();

        $this->customer = $customerEntity;
    }

    /**
     * @return void
     */
    protected function setupProduct()
    {
        $this->productAbstract = (new SpyProductAbstract())
            ->setSku('abstract_sku')
            ->setAttributes('{}');

        $this->productAbstract->save();

        $this->product_1 = $this->createProduct('concrete_sku_1', $this->productAbstract->getIdProductAbstract());
        $this->product_2 = $this->createProduct('concrete_sku_2', $this->productAbstract->getIdProductAbstract());
        $this->product_3 = $this->createProduct('concrete_sku_3', $this->productAbstract->getIdProductAbstract());
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createProduct($sku, $idProductAbstract)
    {
        $productEntity = (new SpyProduct())
            ->setSku($sku)
            ->setAttributes('{}')
            ->setFkProductAbstract($idProductAbstract);

        $productEntity->save();

        return $productEntity;
    }

    /**
     * @return void
     */
    protected function setupBigWishlist()
    {
        for ($a = 0; $a < 100; $a++) {
            $productEntity = $this->createProduct('concrete_sku_many_' . $a, $this->productAbstract->getIdProductAbstract());
            $this->createWishlistItem($this->wishlist->getIdWishlist(), $productEntity->getSku());
        }
    }

    /**
     * @param int $idWishlist
     * @param int $sku
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem
     */
    protected function createWishlistItem($idWishlist, $sku)
    {
        $wishlistItemEntity = (new SpyWishlistItem())
            ->setFkWishlist($idWishlist)
            ->setSku($sku);

        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @return void
     */
    protected function setupWishlist()
    {
        $this->wishlist = (new SpyWishlist())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName(self::DEFAULT_NAME);

        $this->wishlist->save();

        $this->createWishlistItem($this->wishlist->getIdWishlist(), $this->product_1->getSku());
        $this->createWishlistItem($this->wishlist->getIdWishlist(), $this->product_2->getSku());
    }

    /**
     * @return void
     */
    public function testGetWishList()
    {
        $this->setupBigWishlist();

        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
        $this->assertEquals('default', $wishlistTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem()
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_3->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->addItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotThrowExceptionWhenItemAlreadyExists()
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->addItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenItemIsAlreadyRemoved()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->filterBySku($this->product_1->getSku())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenListIsEmpty()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldRemoveItem()
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testCreateWishlistShouldCreateWishlist()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName('foo')
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransfer = $this->wishlistFacade->createWishlist($wishlistTransfer);

        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertWishlistCount(2);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testUpdateWishlistShouldUpdateWishlist()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistTransfer->setName('new name');

        $wishlistTransfer = $this->wishlistFacade->updateWishlist($wishlistTransfer);

        $this->assertEquals('new name', $wishlistTransfer->getName());
        $this->assertEquals($this->wishlist->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertWishlistItemCount(2, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testRemoveWishlistShouldRemoveItemsAsWell()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistTransfer = $this->wishlistFacade->removeWishlist($wishlistTransfer);

        $this->assertWishlistCount(0);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testEmptyWishlistShouldRemoveItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $this->wishlistFacade->emptyWishlist($wishlistTransfer);

        $this->assertWishlistCount(1);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testAddItemCollectionShouldAddItemCollection()
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($this->wishlist->toArray(), true);

        $wishlistItemTransfer_1 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_1->getSku());

        $wishlistItemTransfer_2 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_2->getSku());

        $wishlistItemTransfer_3 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_3->getSku());

        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testGetWishlistOverviewShouldReturnPaginatedResult()
    {
        $this->setupBigWishlist();

        $pageNumber = 5;
        $itemsPerPage = 10;
        $orderBy = SpyWishlistItemTableMap::COL_CREATED_AT;
        $orderDirection = Criteria::DESC;
        $itemsTotal = $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->count();

        $wishlistTransfer = (new WishlistTransfer())
            ->setName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistOverviewRequest = (new WishlistOverviewRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setPage($pageNumber)
            ->setItemsPerPage($itemsPerPage)
            ->setOrderBy($orderBy)
            ->setOrderDirection($orderDirection);

        $wishlistOverviewResponse = $this->wishlistFacade->getWishlistOverview($wishlistOverviewRequest);

        $this->assertInstanceOf(WishlistOverviewResponseTransfer::class, $wishlistOverviewResponse);
        $this->assertEquals('default', $wishlistTransfer->getName());
        $this->assertEquals($pageNumber, $wishlistOverviewResponse->getPagination()->getPage());
        $this->assertEquals($itemsPerPage, $wishlistOverviewResponse->getPagination()->getItemsPerPage());
        $this->assertEquals($itemsTotal, $wishlistOverviewResponse->getPagination()->getItemsTotal());
    }

    /**
     * @param int $expected
     * @param null $idWishlist
     *
     * @return void
     */
    protected function assertWishlistItemCount($expected, $idWishlist = null)
    {
        if (!$idWishlist) {
            $idWishlist = $this->wishlist->getIdWishlist();
        }

        $count = $this->wishlistQueryContainer
            ->queryItemsByWishlistId($idWishlist)
            ->count();

        $this->assertEquals($expected, $count);
    }

    /**
     * @param int $expected
     *
     * @return void
     */
    protected function assertWishlistCount($expected)
    {
        $count = $this->wishlistQueryContainer
            ->queryWishlist()
            ->filterByFkCustomer($this->customer->getIdCustomer())
            ->count();

        $this->assertEquals($expected, $count);
    }

}
