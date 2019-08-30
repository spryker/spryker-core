<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WishlistsRestApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group WishlistsRestApi
 * @group Business
 * @group Facade
 * @group WishlistsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class WishlistsRestApiFacadeTest extends Test
{
    //TODO: refactoring of the class: beautify code
    /**
     * @var \SprykerTest\Zed\WishlistsRestApi\WishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::_setUp();

        $this->customer = $this->tester->haveCustomer();
    }

    /**
     * @return void
     */
    public function testUpdateWishlist(): void
    {
        //Arrange
        $originalName = 'Original';
        $newName = 'New';
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer()
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->updateWishlist(
            (new WishlistRequestTransfer())
                ->setUuid($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setWishlist(
                    (new WishlistTransfer())
                        ->setName($newName)
                )
        );

        //Assert
        $wishlistEntity = $this->tester->findWishlistEntityDirectlyInDatabase(
            $this->customer->getIdCustomer(),
            $wishlist->getUuid()
        );

        $this->assertTrue($wishlistResponseTransfer->getIsSuccess());
        $this->assertEquals($newName, $wishlistResponseTransfer->getWishlist()->getName());
        $this->assertEquals($newName, $wishlistEntity->getName());
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingWishlistShouldReturnError(): void
    {
        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->updateWishlist(
            (new WishlistRequestTransfer())
                ->setUuid("uuid-does-not-exist")
                ->setIdCustomer($this->customer->getIdCustomer())
        );

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistWithWrongNameShouldReturnError(): void
    {
        //Arrange
        $originalName = 'Original';
        $wrongName = '{{New';
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer()
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->updateWishlist(
            (new WishlistRequestTransfer())
                ->setUuid($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setWishlist(
                    (new WishlistTransfer())
                        ->setName($wrongName)
                )
        );

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_CANT_BE_UPDATED
        );
    }

    /**
     * @return void
     */
    public function testDeleteWishlist(): void
    {
        //Arrange
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name'
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->deleteWishlist(
            (new WishlistRequestTransfer())
                ->setUuid($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
        );

        //Assert
        $wishlistEntity = $this->tester->findWishlistEntityDirectlyInDatabase(
            $this->customer->getIdCustomer(),
            $wishlist->getUuid()
        );
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess());
        $this->assertNull($wishlistEntity);
    }

    /**
     * @return void
     */
    public function testDeleteNonExistingWishlistShouldReturnError(): void
    {
        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->deleteWishlist(
            (new WishlistRequestTransfer())
                ->setUuid("uuid-does-not-exist")
                ->setIdCustomer($this->customer->getIdCustomer())
        );

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }


    /**
     * @return void
     */
    public function testAddWishlistItem(): void
    {
        //Arrange
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'name' => 'name',
                'fkCustomer' => $this->customer->getIdCustomer()
            ]
        );
        $concreteProduct = $this->tester->haveProduct();

        //Act
        $wishlistItemResponseTransfer = $this->tester->getFacade()->addItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($concreteProduct->getSku())
        );

        //Assert
        $this->assertCount(1, $wishlistItemResponseTransfer->getWishlist()->getWishlistItems());
        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEmpty($wishlistItemResponseTransfer->getErrors());
        $this->assertNull($wishlistItemResponseTransfer->getErrorIdentifier());
    }

    /**
     * @return void
     */
    public function testAddWishlistItemToNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $concreteProduct = $this->tester->haveProduct();

        //Act
        $wishlistItemResponseTransfer = $this->tester->getFacade()->addItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist("uuid-does-not-exist")
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($concreteProduct->getSku())
        );

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $wishlistItemResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }

    /**
     * @return void
     */
    public function testAddNonExistingWishlistItemToWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'name' => 'name',
                'fkCustomer' => $this->customer->getIdCustomer()
            ]
        );

        //Act
        $wishlistItemResponseTransfer = $this->tester->getFacade()->addItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku("non-existing-sku")
        );

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $wishlistItemResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED
        );
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItem(): void
    {
        //Arrange
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name'
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName()
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getFacade()->deleteItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($wishlistItem->getIdWishlistItem())
        );

        //Assert
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemInNonExistingWishlistShouldReturnError(): void
    {
        $wishlist = $this->tester->haveEmptyWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name'
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName()
            ]
        );

        //Act
        $wishlistItemResponseTransfer = $this->tester->getFacade()->deleteItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist('uuid-does-not-exist')
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($wishlistItem->getSku())
        );

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
    }
}
