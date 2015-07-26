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
class WishlistClientSaveTest extends \PHPUnit_Framework_TestCase
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

        $wishlistitemList = (new \Generated\Shared\Transfer\WishlistTransfer())->setItems(new \ArrayObject($wishlistitem));

        \PHPUnit_Framework_Assert::assertEquals($wishlistitemList, $items);
    }

    /**
     * @group WishlistClientTestAdd
     */
    public function testUpdateToSessionWishlistItem()
    {
        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136823");
        $product->setAbstractSku("136823");

        //When
        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);
        $wishlistitem->setQuantity(3);
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

        $wishlistitemList = (new \Generated\Shared\Transfer\WishlistTransfer())->setItems(new \ArrayObject($wishlistitem));

        \PHPUnit_Framework_Assert::assertEquals($wishlistitemList, $items);

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


        $this->assertEquals(0, count($wishlist->getItems()));

    }



}
