<?php

namespace Functional\SprykerFeature\Client\Wishlist\Service;

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

    use WishlistClientTestSetup;

    public function setUp()
    {
        parent::setUp();

        $this->wishlistClient = $this->getWishlistClient();

    }

    /**
     * @group WishlistClientTestAdd
     * @group WishlistClientSessionTest
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

        $this->wishlistClient
            ->saveItem($wishlistitem);

        //Then
        $items = $this->wishlistClient
            ->getWishlist();


        \PHPUnit_Framework_Assert::assertEquals($wishlistitem, $items->getItems()[0]);
    }

    /**
     * @group WishlistClientTestUpdateAdd
     * @group WishlistClientSessionTest
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

        $this->wishlistClient->saveItem($wishlistitem);

        // And
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136824");
        $product->setAbstractSku("136823");


        $wishlistitem2 = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem2->setProduct($product);
        $wishlistitem2->setQuantity(2);
        $wishlistitem2->setAddedAt(time());

        $this->wishlistClient->saveItem($wishlistitem2);


        //Then
        $items = $this->wishlistClient->getWishlist();


        \PHPUnit_Framework_Assert::assertEquals(2, count($items->getItems()));
        \PHPUnit_Framework_Assert::assertEquals(3, $items->getItems()[0]->getQuantity());
        \PHPUnit_Framework_Assert::assertEquals(2, $items->getItems()[1]->getQuantity());

        return $items;
    }

    /**
     * @group WishlistClientSessionTest
     * @group WishlistClientTestDelete
     * @depends testUpdateToSessionWishlistItem
     */
    public function testRemoveWhislistItem()
    {
        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("136823");
        $product->setAbstractSku("136823");

        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);

        // When
        $this->wishlistClient->removeItem($wishlistitem);

        //Then
        $wishlist = $this->wishlistClient->getWishlist();

        $this->assertEquals(1, count($wishlist->getItems()));
    }
}
