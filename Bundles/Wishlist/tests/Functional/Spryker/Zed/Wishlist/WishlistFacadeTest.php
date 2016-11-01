<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Wishlist;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
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
     * @var int
     */
    protected $idProduct;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @var int
     */
    protected $idCustomer;

    protected function setUp()
    {
        $this->wishlistQueryContainer = new WishlistQueryContainer();
        $this->wishlistFacade = new WishlistFacade();

        $this->setupData();
    }

    protected function setupData()
    {
        $customer = new SpyCustomer();
        $customer->fromArray([
            'customer_reference' => 'customer_reference',
            'email' => 'email'
        ]);
        $customer->save();

        $this->idCustomer = $customer->getIdCustomer();

        $productAbstract = new SpyProductAbstract();
        $productAbstract->fromArray([
            'sku' => 'abstract_sku',
            'attributes' => '{}',
        ]);
        $productAbstract->save();

        $this->idProductAbstract = $productAbstract->getIdProductAbstract();

        $product = new SpyProduct();
        $product->fromArray([
            'sku' => 'concrete_sku_1',
            'attributes' => '{}',
            'fk_product_abstract' => $productAbstract->getIdProductAbstract()
        ]);
        $product->save();

        $wishlist = new SpyWishlist();
        $wishlist->fromArray([
            'fk_customer' => $customer->getIdCustomer(),
            'fk_product' => $product->getIdProduct()
        ]);
        $wishlist->save();

        $product = new SpyProduct();
        $product->fromArray([
            'sku' => 'concrete_sku_2',
            'attributes' => '{}',
            'fk_product_abstract' => $productAbstract->getIdProductAbstract()
        ]);
        $product->save();

        $this->idProduct = $product->getIdProduct();

        $wishlist = new SpyWishlist();
        $wishlist->fromArray([
            'fk_customer' => $customer->getIdCustomer(),
            'fk_product' => $product->getIdProduct()
        ]);
        $wishlist->save();
    }

    /**
     * @return void
     */
    public function testGetWishListShouldReturnTransfer()
    {
        $wishlistTransfer = $this->wishlistFacade->getCustomerWishlist($this->idCustomer);

        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
    }

    /**
     * @return void
     */
    public function testGetWishListShouldReturnTransferWithTwoItems()
    {
        $wishlistTransfer = $this->wishlistFacade->getCustomerWishlist($this->idCustomer);

        $this->assertCount(2, $wishlistTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem()
    {
        $product = new SpyProduct();
        $product->fromArray([
            'sku' => 'concrete_sku_3',
            'attributes' => '{}',
            'fk_product_abstract' => $this->idProductAbstract
        ]);
        $product->save();

        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkCustomer($this->idCustomer)
            ->setFkProduct($product->getIdProduct());

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItemOnlyOnce()
    {
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setFkCustomer($this->idCustomer)
            ->setFkProduct($this->idProduct);

        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @param int $expected
     *
     * @return void
     */
    protected function assertWishlistItemCount($expected)
    {
        $count = $this->wishlistQueryContainer
            ->queryWishlistByCustomerId($this->idCustomer)
            ->count();

        $this->assertEquals($expected, $count);
    }

}
