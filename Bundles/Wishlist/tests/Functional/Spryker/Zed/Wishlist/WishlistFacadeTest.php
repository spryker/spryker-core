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
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItemQuery;
use Orm\Zed\Wishlist\Persistence\SpyWishlistQuery;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;
use Spryker\Zed\Wishlist\Business\Exception\WishlistExistsException;
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
        $customerQuery = new SpyCustomerQuery();
        $customerEntity = $customerQuery
            ->filterByCustomerReference('customer_reference')
            ->filterByEmail('email')
            ->findOneOrCreate();

        $customerEntity->save();

        $this->customer = $customerEntity;
    }

    /**
     * @return void
     */
    protected function setupProduct()
    {
        $productAbstractQuery = new SpyProductAbstractQuery();
        $this->productAbstract = $productAbstractQuery
            ->filterBySku('abstract_sku')
            ->filterByAttributes('{}')
            ->findOneOrCreate();

        $this->productAbstract->save();

        $this->product_1 = $this->findOrCreateProduct('concrete_sku_1', $this->productAbstract->getIdProductAbstract());
        $this->product_2 = $this->findOrCreateProduct('concrete_sku_2', $this->productAbstract->getIdProductAbstract());
        $this->product_3 = $this->findOrCreateProduct('concrete_sku_3', $this->productAbstract->getIdProductAbstract());
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function findOrCreateProduct($sku, $idProductAbstract)
    {
        $productQuery = new SpyProductQuery();
        $productEntity = $productQuery
            ->filterBySku($sku)
            ->filterByAttributes('{}')
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOneOrCreate();

        $productEntity->save();

        return $productEntity;
    }

    /**
     * @return void
     */
    protected function setupBigWishlist()
    {
        for ($a = 0; $a < 100; $a++) {
            $productEntity = $this->findOrCreateProduct('concrete_sku_many_' . $a, $this->productAbstract->getIdProductAbstract());
            $this->findOrCreateWishlistItem($this->wishlist->getIdWishlist(), $productEntity->getIdProduct());
        }
    }

    /**
     * @param int $idWishlist
     * @param int $idProduct
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem
     */
    protected function findOrCreateWishlistItem($idWishlist, $idProduct)
    {
        $wishlistItemQuery = new SpyWishlistItemQuery();
        $wishlistItemEntity = $wishlistItemQuery
            ->filterByFkWishlist($idWishlist)
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @return void
     */
    protected function setupWishlist()
    {
        $wishlistQuery = new SpyWishlistQuery();
        $this->wishlist = $wishlistQuery
            ->filterByFkCustomer($this->customer->getIdCustomer())
            ->filterByName('Default')
            ->findOneOrCreate();

        $this->wishlist->save();

        $this->findOrCreateWishlistItem($this->wishlist->getIdWishlist(), $this->product_1->getIdProduct());
        $this->findOrCreateWishlistItem($this->wishlist->getIdWishlist(), $this->product_2->getIdProduct());
    }

    /**
     * @return void
     */
    public function testGetWishListShouldReturnTransferWithTwoItems()
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

        $this->assertCount(2, $wishlistTransfer->getItems());
    }

    /**
     * TODO no rollback on exception
     *
     * @return void
     */
    public function testGetWishListShouldThrowException()
    {
        $this->expectException(MissingWishlistException::class);
        $this->expectExceptionMessage(sprintf(
            'Wishlist: INVALIDNAME for customer with id: %d not found',
            $this->customer->getIdCustomer()
        ));

        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName('INVALIDNAME');

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);
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

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

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
     * TODO no rollback on exception
     *
     * @return void
     */
    public function testUpdateWishlistShouldCheckUniqueName()
    {
        $this->expectException(WishlistExistsException::class);
        $this->expectExceptionMessage(sprintf(
            'Wishlist with name: Default2 for customer: %d already exists',
            $this->customer->getIdCustomer()
        ));

        $wishlist = new SpyWishlist();
        $wishlist->fromArray([
            'fk_customer' => $this->customer->getIdCustomer(),
            'name' => 'Default2'
        ]);
        $wishlist->save();

        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );
        $wishlistTransfer->setName('Default2');

        $wishlistTransfer = $this->wishlistFacade->updateWishlist($wishlistTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateWishlistShouldUpdateWishlistButNotItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
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
     * TODO fix rollback when exception is thrown
     *
     * @return void
     */
    public function testUpdateWishlistShouldThrowException()
    {
        $this->expectException(MissingWishlistException::class);

        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistTransfer->setIdWishlist(1231231);

        $wishlistTransfer = $this->wishlistFacade->updateWishlist($wishlistTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemCollectionShouldAddItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistItem1 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_1->getIdProduct());
        $wishlistItem2 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_2->getIdProduct());
        $wishlistItem3 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_3->getIdProduct());

        $wishlistTransfer->setItems(new \ArrayObject([
            $wishlistItem1, $wishlistItem2, $wishlistItem3
        ]));

        $wishlistTransfer = $this->wishlistFacade->addItemCollection($wishlistTransfer);

        $this->assertWishlistItemCount(3, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testRemoveItemCollectionShouldRemoveItems()
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistItem1 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_1->getIdProduct());
        $wishlistItem2 = (new WishlistItemTransfer())
            ->setFkProduct($this->product_2->getIdProduct());

        $wishlistTransfer->setItems(new \ArrayObject([
            $wishlistItem1, $wishlistItem2
        ]));

        $wishlistTransfer = $this->wishlistFacade->removeItemCollection($wishlistTransfer);

        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
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
