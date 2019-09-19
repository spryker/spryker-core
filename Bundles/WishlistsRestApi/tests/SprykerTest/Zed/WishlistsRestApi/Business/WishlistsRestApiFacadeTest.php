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
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;

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
    protected const WRONG_WISHLIST_UUID = 'uuid-does-not-exist';

    /**
     * @var \SprykerTest\Zed\WishlistsRestApi\WishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\WishlistsRestApi\Business\WishlistsRestApiFacadeInterface
     */
    protected $facade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::_setUp();

        $this->customer = $this->tester->haveCustomer();
        $this->facade = $this->tester->getWishlistsRestApiFacade();
    }

    /**
     * @return void
     */
    public function testUpdateWishlist(): void
    {
        $originalName = 'Original';
        $newName = 'New';
        $originalWishlist = $this->tester->haveWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist(
            (new WishlistRequestTransfer())
                ->setUuid($originalWishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setWishlist(
                    (new WishlistTransfer())
                        ->setName($newName)
                )
        );

        //Assert
        $wishlistTransfer = $this->tester->getWishlistFacade()
            ->getWishlistByName(
                (new WishlistTransfer())
                    ->setName($newName)
                    ->setFkCustomer($this->customer->getIdCustomer())
            );

        $actualWishlistTransfer = $wishlistResponseTransfer->getWishlist();
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess());
        $this->assertNotNull($actualWishlistTransfer);
        $this->assertEquals($actualWishlistTransfer->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertEquals($actualWishlistTransfer->getName(), $wishlistTransfer->getName());
        $this->assertEquals($actualWishlistTransfer->getName(), $newName);
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingWishlistShouldReturnError(): void
    {
        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist(
            (new WishlistRequestTransfer())
                ->setUuid(static::WRONG_WISHLIST_UUID)
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
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist(
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
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT
        );
    }

    /**
     * @return void
     */
    public function testDeleteWishlistWillThrowWhenNoWishlistTransferProvided(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => $wishlistName,
            ]
        );

        //Act
        $this->facade->deleteWishlist(
            (new WishlistRequestTransfer())
                ->setUuid($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
        );

        //Assert
        $this->expectException(MissingWishlistException::class);
        $this->tester->getWishlistFacade()
            ->getWishlistByName(
                (new WishlistTransfer())
                    ->setName($wishlistName)
                    ->setFkCustomer($this->customer->getIdCustomer())
            );
    }

    /**
     * @return void
     */
    public function testDeleteNonExistingWishlistShouldReturnError(): void
    {
        //Act
        $wishlistResponseTransfer = $this->facade->deleteWishlist(
            (new WishlistRequestTransfer())
                ->setUuid(static::WRONG_WISHLIST_UUID)
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
    public function testAddWishlistItemShouldAddItem(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => $wishlistName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $concreteProduct = $this->tester->haveProduct();

        //Act
        $wishlistItemResponseTransfer = $this->facade->addWishlistItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($concreteProduct->getSku())
        );

        //Assert
        $wishlistTransfer = $this->tester->getWishlistFacade()
            ->getWishlistByName(
                (new WishlistTransfer())
                    ->setName($wishlistName)
                    ->setFkCustomer($this->customer->getIdCustomer())
            );

        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEmpty($wishlistItemResponseTransfer->getErrors());
        $this->assertNull($wishlistItemResponseTransfer->getErrorIdentifier());
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertEquals(1, $wishlistTransfer->getNumberOfItems());
        $this->assertEquals(
            $concreteProduct->getSku(),
            $wishlistItemResponseTransfer->getWishlistItem()->getSku()
        );
    }

    /**
     * @return void
     */
    public function testAddWishlistItemToNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $concreteProduct = $this->tester->haveProduct();

        //Act
        $wishlistItemResponseTransfer = $this->facade->addWishlistItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist(static::WRONG_WISHLIST_UUID)
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
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => 'name',
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );

        //Act
        $wishlistItemResponseTransfer = $this->facade->addWishlistItem(
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
    public function testDeleteWishlistItemShouldDeleteItem(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => $wishlistName,
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName(),
            ]
        );

        //Act
        $wishlistItemResponseTransfer = $this->facade->deleteItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist($wishlist->getUuid())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($wishlistItem->getSku())
        );

        //Assert
        $wishlistTransfer = $this->tester->getWishlistFacade()
            ->getWishlistByName(
                (new WishlistTransfer())
                    ->setName($wishlistName)
                    ->setFkCustomer($this->customer->getIdCustomer())
            );

        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertCount(0, $wishlistTransfer->getWishlistItems());
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemInNonExistingWishlistShouldReturnError(): void
    {
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name',
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName(),
            ]
        );

        //Act
        $wishlistItemResponseTransfer = $this->facade->deleteItem(
            (new WishlistItemRequestTransfer())
                ->setUuidWishlist(static::WRONG_WISHLIST_UUID)
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setSku($wishlistItem->getSku())
        );

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEquals(
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND,
            $wishlistItemResponseTransfer->getErrorIdentifier()
        );
    }
}
