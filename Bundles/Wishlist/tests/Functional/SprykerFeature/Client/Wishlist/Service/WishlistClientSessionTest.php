<?php

namespace Functional\SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group SprykerFeature
 * @group Client
 * @group Wishlist
 * @group Service
 * @group WishlistClientTest
 */
class WishlistClientSessionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @group WishlistClientTestAdd
     */
    public function testAddToSessionWishlistItem()
    {
        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136823");
        $product->setAbstractSku("136823");

        //When
        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);
        $wishlistitem->setQuantity(2);
        $wishlistitem->setAddedAt(time());

        Locator::getInstance()
            ->wishlist()
            ->client()
            ->saveItem($wishlistitem);

        //Then
        $items = Locator::getInstance()
            ->wishlist()
            ->client()
            ->getWishlist();


        \PHPUnit_Framework_Assert::assertEquals($wishlistitem, $items->getItems()[0]);
    }

    /**
     * @group WishlistClientTestUpdateAdd
     */
    public function testUpdateToSessionWishlistItem()
    {
        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136823");
        $product->setAbstractSku("136823");


        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);
        $wishlistitem->setQuantity(3);
        $wishlistitem->setAddedAt(time());

        Locator::getInstance()
            ->wishlist()
            ->client()
            ->saveItem($wishlistitem);

        // And
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136824");
        $product->setAbstractSku("136823");


        $wishlistitem2 = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem2->setProduct($product);
        $wishlistitem2->setQuantity(2);
        $wishlistitem2->setAddedAt(time());

        Locator::getInstance()
            ->wishlist()
            ->client()
            ->saveItem($wishlistitem2);


        //Then
        $items = Locator::getInstance()
            ->wishlist()
            ->client()
            ->getWishlist();


        \PHPUnit_Framework_Assert::assertEquals(2, count($items->getItems()));
        \PHPUnit_Framework_Assert::assertEquals(3, $items->getItems()[0]->getQuantity());
        \PHPUnit_Framework_Assert::assertEquals(2, $items->getItems()[1]->getQuantity());

        return $items;
    }

    /**
     * @group WishlistClientTestDelete
     * @depends testUpdateToSessionWishlistItem
     */
    public function testRemoveWhislistItem($items)
    {

        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136823");
        $product->setAbstractSku("136823");

        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);

        // When
        Locator::getInstance()
            ->wishlist()
            ->client()
            ->removeItem($wishlistitem);

        //Then
        $wishlist = Locator::getInstance()
            ->wishlist()->client()->getWishlist();


        $this->assertEquals(1, count($wishlist->getItems()));
    }
}
