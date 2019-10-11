<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Wishlist\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Wishlist\Business\WishlistFacade;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Facade
 * @group WishlistFacadeTest
 * Add your own group annotations below this line
 */
class WishlistFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Wishlist\WishlistBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Wishlist\Business\WishlistFacadeInterface
     */
    protected $wishlistFacade;

    /**
     * @var \Spryker\Zed\Wishlist\Persistence\WishlistQueryContainerInterface
     */
    protected $wishlistQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product_1;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product_2;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $product_3;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @var \Generated\Shared\Transfer\WishlistTransfer
     */
    protected $wishlist;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->wishlistQueryContainer = new WishlistQueryContainer();
        $this->wishlistFacade = new WishlistFacade();

        $this->product_1 = $this->tester->haveProduct();
        $this->product_2 = $this->tester->haveProduct();
        $this->product_3 = $this->tester->haveProduct();
        $this->customer = $this->tester->haveCustomer();
        $this->wishlist = $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlist->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->product_1->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlist->getName(),
        ]);

        $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlist->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->product_2->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlist->getName(),
        ]);
    }

    /**
     * @return void
     */
    protected function addItemsToWishlist(): void
    {
        for ($i = 0; $i < 25; $i++) {
            $this->tester->haveItemInWishlist([
                WishlistItemTransfer::FK_WISHLIST => $this->wishlist->getIdWishlist(),
                WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
                WishlistItemTransfer::SKU => $this->tester->haveProduct()->getSku(),
                WishlistItemTransfer::WISHLIST_NAME => $this->wishlist->getName(),
            ]);
        }
    }

    /**
     * @return void
     */
    public function testGetWishListByName(): void
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
        $this->assertEquals($this->wishlist->getName(), $wishlistTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem(): void
    {
        $WishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_3->getSku());

        $WishlistItemTransfer = $this->wishlistFacade->addItem($WishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $WishlistItemTransfer);
        $this->assertWishlistItemCount(3);
        $this->assertNotEmpty($WishlistItemTransfer->getIdWishlistItem());
    }

    /**
     * @return void
     */
    public function testAddNonExistingItemShouldSkipItem(): void
    {
        $WishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku('non-existing-sku');

        $WishlistItemTransfer = $this->wishlistFacade->addItem($WishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $WishlistItemTransfer);
        $this->assertEmpty($WishlistItemTransfer->getIdWishlistItem());
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotThrowExceptionWhenItemAlreadyExists(): void
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->addItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenItemIsAlreadyRemoved(): void
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->filterBySku($this->product_1->getSku())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenListIsEmpty(): void
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldRemoveItem(): void
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testCreateWishlistShouldCreateWishlist(): void
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
    public function testValidateAndCreateWishlistShouldCreateWishlist(): void
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName('foo')
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndCreateWishlist($wishlistTransfer);

        $this->assertTrue($wishlistTransferResponseTransfer->getIsSuccess());

        $wishlistTransfer = $wishlistTransferResponseTransfer->getWishlist();
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertWishlistCount(2);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testValidateAndCreateWishlistShouldFailWhenNameIsNotUnique(): void
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndCreateWishlist($wishlistTransfer);

        $this->assertFalse($wishlistTransferResponseTransfer->getIsSuccess());
        $this->assertCount(1, $wishlistTransferResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateWishlistShouldUpdateWishlist(): void
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
    public function testValidateAndUpdateWishlistShouldUpdateWishlist(): void
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true
        );

        $wishlistTransfer->setName('new name');

        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        $this->assertTrue($wishlistTransferResponseTransfer->getIsSuccess());

        $wishlistTransfer = $wishlistTransferResponseTransfer->getWishlist();
        $this->assertEquals('new name', $wishlistTransfer->getName());
        $this->assertEquals($this->wishlist->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertWishlistItemCount(2, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testValidateAndUpdateWishlistShouldFailWhenNameIsNotUnique(): void
    {
        $wishlistTransfer = new WishlistTransfer();

        $newWishlistId = $this->wishlist->getIdWishlist() + 1;

        $wishlistTransfer
            ->setIdWishlist($newWishlistId)
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        $this->assertFalse($wishlistTransferResponseTransfer->getIsSuccess());
        $this->assertCount(1, $wishlistTransferResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testRemoveWishlistShouldRemoveItemsAsWell(): void
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
    public function testRemoveWishlistByNameShouldRemoveItemsAsWell(): void
    {
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransfer = $this->wishlistFacade->removeWishlistByName($wishlistTransfer);

        $this->assertWishlistCount(0);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testEmptyWishlistShouldRemoveItems(): void
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
    public function testAddItemCollectionShouldAddItemCollection(): void
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($this->wishlist->toArray(), true);

        $wishlistItemTransfer_1 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_1->getSku());

        $wishlistItemTransfer_2 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_2->getSku());

        $wishlistItemTransfer_3 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_3->getSku());

        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testRemoveItemCollectionShouldRemoveOnlySelectedItems(): void
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($this->wishlist->toArray(), true);

        $wishlistItemTransfer_1 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_1->getSku());

        $wishlistItemTransfer_2 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_2->getSku());

        $wishlistItemTransfer_3 = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setSku($this->product_3->getSku());

        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        $this->assertWishlistItemCount(3);

        $wishlistItemCollectionTransfer = new WishlistItemCollectionTransfer();
        $wishlistItemCollectionTransfer
            ->addItem($wishlistItemTransfer_1)
            ->addItem($wishlistItemTransfer_2);

        $this->wishlistFacade->removeItemCollection($wishlistItemCollectionTransfer);

        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testGetWishlistOverviewShouldReturnPaginatedResult(): void
    {
        $this->addItemsToWishlist();

        $pageNumber = 3;
        $itemsPerPage = 10;
        $orderBy = SpyWishlistItemTableMap::COL_CREATED_AT;
        $orderDirection = Criteria::DESC;
        $itemsTotal = $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->count();

        $wishlistTransfer = (new WishlistTransfer())
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistOverviewRequest = (new WishlistOverviewRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setPage($pageNumber)
            ->setItemsPerPage($itemsPerPage)
            ->setOrderBy($orderBy)
            ->setOrderDirection($orderDirection);

        $wishlistOverviewResponse = $this->wishlistFacade->getWishlistOverview($wishlistOverviewRequest);

        $this->assertInstanceOf(WishlistOverviewResponseTransfer::class, $wishlistOverviewResponse);
        $this->assertEquals($this->wishlist->getName(), $wishlistTransfer->getName());
        $this->assertEquals($pageNumber, $wishlistOverviewResponse->getPagination()->getPage());
        $this->assertEquals($itemsPerPage, $wishlistOverviewResponse->getPagination()->getItemsPerPage());
        $this->assertEquals($itemsTotal, $wishlistOverviewResponse->getPagination()->getItemsTotal());
    }

    /**
     * @return void
     */
    public function testGetWishlistsByCustomerReturnPersistedWishlists(): void
    {
        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);

        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($this->customer);

        $this->assertCount(3, $wishlistCollectionTransfer->getWishlists(), 'Customer wishlist collection should contain expected number of wishlists.');
    }

    /**
     * @return void
     */
    public function testGetWishlistByFilterShouldReturnWishlistByName(): void
    {
        //Arrange
        $wishlistTransfer = $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->product_1->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
        ]);

        $wishlistFilterTransfer = (new WishlistFilterTransfer())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setName($wishlistTransfer->getName());

        //Act
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter($wishlistFilterTransfer);

        //Assert
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess(), 'Wishlist response is unsuccessful.');
        $this->assertEmpty($wishlistResponseTransfer->getErrors(), 'Unexpected errors returned in response.');
        $this->assertNull($wishlistResponseTransfer->getErrorIdentifier(), 'Error identifier is supposed to be empty.');
        $this->assertNotNull($wishlistResponseTransfer->getWishlist(), 'No wishlist returned.');
        $this->assertEquals($wishlistTransfer->getName(), $wishlistResponseTransfer->getWishlist()->getName(), 'Wishlist name is different.');
        $this->assertCount(1, $wishlistResponseTransfer->getWishlist()->getWishlistItems(), 'Returned wishlist items amount is not expected.');
        $this->assertEquals($this->product_1->getSku(), $wishlistResponseTransfer->getWishlist()->getWishlistItems()[0]->getSku(), 'Wishlist item sku is unexpected.');
    }

    /**
     * @return void
     */
    public function testGetWishlistByFilterShouldReturnErrorInCaseWishlistIsNotFoundByName(): void
    {
        //Arrange
        $wishlistFilterTransfer = (new WishlistFilterTransfer())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setName('fake-name');

        //Act
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter($wishlistFilterTransfer);

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess(), 'Wishlist response should be unsuccessful.');
        $this->assertCount(1, $wishlistResponseTransfer->getErrors(), 'Exactly 1 error is expected');
        $this->assertNull($wishlistResponseTransfer->getErrorIdentifier(), 'Error identifier is supposed to be empty.');
        $this->assertNull($wishlistResponseTransfer->getWishlist(), 'No wishlist should be returned.');
    }

    /**
     * @param int $expected
     * @param int|null $idWishlist
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
