<?php

namespace Functional\SprykerFeature\Client\Wishlist\Service;

use Codeception\TestCase\Test;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Zed\Kernel\InternalClassBuilderForTests;


/**
 * @group SprykerFeature
 * @group Client
 * @group Wishlist
 * @group Service
 * @group WishlistClientTest
 */

class WishlistClientDatabaseTest extends Test
{
    protected $wishlistClient;

    use InternalClassBuilderForTests;

    use WishlistClientTestSetup;

    public function setUp()
    {
        parent::setUp();

        $this->wishlistClient = $this->getWishlistClientByCustomer();

        $this->setTestData();
    }


    /**
     * @group WishlistClientDatabaseTest
     */
    public function testAddItem()
    {
        //Given
        $product = new \Generated\Shared\Transfer\WishlistProductTransfer();
        $product->setConcreteSku("test1");
        $product->setAbstractSku("test2");

        $time = time();

        $wishlistitem = new \Generated\Shared\Transfer\WishlistItemTransfer();
        $wishlistitem->setProduct($product);
        $wishlistitem->setQuantity(2);
        $wishlistitem->setAddedAt($time);


        //When
        $this->wishlistClient->saveItem($wishlistitem);

        $wishlistItems = $this->wishlistClient->getWishlist()->getItems()->getArrayCopy();

        $lastWishlistItem =  array_pop($wishlistItems);
        $expectedDatetime = (array) date_timestamp_set(new \DateTime(), $time);
        $createdDatetime = $lastWishlistItem->getAddedAt();

        $this->assertEquals($expectedDatetime, $createdDatetime);
        $this->assertTrue(is_int($lastWishlistItem->getId()) && $lastWishlistItem->getId()>0, "Wishlist Item Id should be large 0");

        return $lastWishlistItem;
    }


    /**
     * @group WishlistClientDatabaseTest
     * @depends testAddItem
     */
    public function testUpdateItem(WishlistItemInterface $wishlistitem)
    {
        //Given
        $wishlistitem->setQuantity(4);

        //When
        $this->wishlistClient->saveItem($wishlistitem);

        $wishlistItems = $this->wishlistClient->getWishlist()->getItems()->getArrayCopy();
        $lastWishlistItem =  array_pop($wishlistItems);

        // Then
        $this->assertEquals($wishlistitem, $lastWishlistItem);

        return $lastWishlistItem;
    }

    /**
     * @depends testUpdateItem
     * @group WishlistClientDatabaseTest
     */
    public function testRemoveItem(WishlistItemInterface $wishlistitem)
    {
        //When
        $this->wishlistClient->removeItem($wishlistitem);

        $wishlistItems = $this->wishlistClient->getWishlist()->getItems()->getArrayCopy();
        $lastWishlistItem =  array_pop($wishlistItems);

        // Then
        $this->assertTrue($wishlistitem->getId()>$lastWishlistItem->getId());
    }






}
