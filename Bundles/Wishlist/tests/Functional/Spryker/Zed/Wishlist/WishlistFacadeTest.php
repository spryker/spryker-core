<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Wishlist;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
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
        $this->customer = new SpyCustomer();
        $this->customer->fromArray([
                'customer_reference' => 'customer_reference',
                'email' => 'email'
            ]);
        $this->customer->save();
    }

    /**
     * @return void
     */
    protected function setupProduct()
    {
        $this->productAbstract = new SpyProductAbstract();
        $this->productAbstract->fromArray([
            'sku' => 'abstract_sku',
            'attributes' => '{}',
        ]);
        $this->productAbstract->save();

        $this->product_1 = new SpyProduct();
        $this->product_1->fromArray([
            'sku' => 'concrete_sku_1',
            'attributes' => '{}',
            'fk_product_abstract' => $this->productAbstract->getIdProductAbstract()
        ]);
        $this->product_1->save();

        $this->product_2 = new SpyProduct();
        $this->product_2->fromArray([
            'sku' => 'concrete_sku_2',
            'attributes' => '{}',
            'fk_product_abstract' => $this->productAbstract->getIdProductAbstract()
        ]);
        $this->product_2->save();

        $this->product_3 = new SpyProduct();
        $this->product_3->fromArray([
            'sku' => 'concrete_sku_3',
            'attributes' => '{}',
            'fk_product_abstract' => $this->productAbstract->getIdProductAbstract()
        ]);
        $this->product_3->save();
    }

    /**
     * @return void
     */
    protected function setupBigWishlist()
    {
        for ($a=0; $a<100; $a++) {
            $product = new SpyProduct();
            $product->fromArray([
                'sku' => 'concrete_sku_many_' . $a,
                'attributes' => '{}',
                'fk_product_abstract' => $this->productAbstract->getIdProductAbstract()
            ]);
            $product->save();

            $wishlistItem = new SpyWishlistItem();
            $wishlistItem->fromArray([
                'fk_wishlist' => $this->wishlist->getIdWishlist(),
                'fk_product' => $product->getIdProduct()
            ]);
            $wishlistItem->save();
        }
    }

    /**
     * @return void
     */
    protected function setupWishlist()
    {
        $this->wishlist = new SpyWishlist();
        $this->wishlist->fromArray([
            'fk_customer' => $this->customer->getIdCustomer(),
            'name' => 'Default'
        ]);
        $this->wishlist->save();

        $wishlistItem = new SpyWishlistItem();
        $wishlistItem->fromArray([
            'fk_wishlist' => $this->wishlist->getIdWishlist(),
            'fk_product' => $this->product_1->getIdProduct()
        ]);
        $wishlistItem->save();

        $wishlistItem = new SpyWishlistItem();
        $wishlistItem->fromArray([
            'fk_wishlist' => $this->wishlist->getIdWishlist(),
            'fk_product' => $this->product_2->getIdProduct()
        ]);
        $wishlistItem->save();
    }

    /**
     * @return void
     */
    public function SKIP_testGetWishListShouldReturnTransfer()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransfer = $this->wishlistFacade->getCustomerWishlistCollection($wishlistTransfer);

        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
    }

    /**
     * @return void
     */
    public function SKIP_testGetWishListShouldReturnTransferWithTwoItems()
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        $wishlistTransfer = $this->wishlistFacade->getCustomerWishlistByName($wishlistTransfer);

        $this->assertCount(2, $wishlistTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testGetWishListShouldReturnFilteredItems()
    {
        $this->setupBigWishlist();

        $filter = (new FilterTransfer())
            ->setLimit(50)
            ->setOffset(0);

        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName())
            ->setItemsFilter($filter);

        $wishlistTransfer = $this->wishlistFacade->getCustomerWishlistByName($wishlistTransfer);

        $this->assertCount(50, $wishlistTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem()
    {
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkWishlist($this->wishlist->getIdWishlist())
            ->setFkProduct($this->product_3->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotThrowExceptionWhenItemAlreadyExists()
    {
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkWishlist($this->wishlist->getIdWishlist())
            ->setFkProduct($this->product_1->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @return void
     */
    public function testRemoveShouldNotThrowExceptionWhenItemIsAlreadyRemoved()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->filterByFkProduct($this->product_1->getIdProduct())
            ->delete();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkWishlist($this->wishlist->getIdWishlist())
            ->setFkProduct($this->product_1->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->removeItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testRemoveShouldNotThrowExceptionWhenListIsEmpty()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->delete();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkWishlist($this->wishlist->getIdWishlist())
            ->setFkProduct($this->product_1->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->removeItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldRemoveItem()
    {
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkWishlist($this->wishlist->getIdWishlist())
            ->setFkProduct($this->product_1->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->removeItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
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
    public function testCreateWishlistShouldCreateWishlistAndItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName('foo')
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistItem1 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_1->getIdProduct());
        $wishlistItem2 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_2->getIdProduct());
        $wishlistItem3 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_3->getIdProduct());

        $wishlistTransfer->setItems(new \ArrayObject([
            $wishlistItem1, $wishlistItem2, $wishlistItem3
        ]));

        $wishlistTransfer = $this->wishlistFacade->createWishlist($wishlistTransfer);

        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertWishlistCount(2);
        $this->assertWishlistItemCount(3, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testUpdateWishlistShouldUpdateWishlist()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(), true
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
    public function testUpdateWishlistShouldUpdateWishlistButNotItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(), true
        );

        $wishlistTransfer->setName('new name');

        $wishlistItem1 = (new WishlistItemTransfer())
            ->setFkWishlist(1231231)
            ->setFkProduct(11231);

        $wishlistTransfer->setItems(new \ArrayObject([
            $wishlistItem1
        ]));

        $wishlistTransfer = $this->wishlistFacade->updateWishlist($wishlistTransfer);

        $this->assertEquals('new name', $wishlistTransfer->getName());
        $this->assertEquals($this->wishlist->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertWishlistItemCount(2, $wishlistTransfer->getIdWishlist());
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
