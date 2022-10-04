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
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
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
     * @var string
     */
    protected const NON_EXISTING_WISHLIST_NAME = 'non_existing_wishlist_name';

    /**
     * @uses \Spryker\Zed\Wishlist\Business\Model\Writer::DEFAULT_NAME
     *
     * @var string
     */
    protected const DEFAULT_WISHLIST_NAME = 'default';

    /**
     * @var string
     */
    protected const NOT_VALID_WISHLIST_NAME = 'not/valid.wishlist-name';

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
     * @var \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected $wishlistItem_1;

    /**
     * @var \Generated\Shared\Transfer\WishlistItemTransfer
     */
    protected $wishlistItem_2;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->wishlistQueryContainer = new WishlistQueryContainer();
        $this->wishlistFacade = new WishlistFacade();

        $this->product_1 = $this->tester->haveProduct();
        $this->product_2 = $this->tester->haveProduct();
        $this->product_3 = $this->tester->haveProduct();
        $this->customer = $this->tester->haveCustomer();
        $this->wishlist = $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->wishlistItem_1 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $this->wishlist->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->product_1->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $this->wishlist->getName(),
        ]);

        $this->wishlistItem_2 = $this->tester->haveItemInWishlist([
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
        // Arrange
        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        // Act
        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

        // Assert
        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
        $this->assertSame($this->wishlist->getName(), $wishlistTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_3->getSku());

        // Act
        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertWishlistItemCount(3);
        $this->assertNotEmpty($wishlistItemTransfer->getIdWishlistItem());
    }

    /**
     * @return void
     */
    public function testAddNonExistingItemShouldSkipItem(): void
    {
        // Arrange
        $WishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku('non-existing-sku');

        // Act
        $WishlistItemTransfer = $this->wishlistFacade->addItem($WishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $WishlistItemTransfer);
        $this->assertEmpty($WishlistItemTransfer->getIdWishlistItem());
    }

    /**
     * @return void
     */
    public function testAddItemToTheWishlistWithInvalidNameDoesNotCreateWishlist(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName(static::NOT_VALID_WISHLIST_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        // Act
        $wishlistItemTransfer = $this->wishlistFacade->addItem($wishlistItemTransfer);
        $wishlistTransfer = $this->tester->findWishlistByFilter(
            (new WishlistFilterTransfer())
                ->setName(static::NOT_VALID_WISHLIST_NAME)
                ->setIdCustomer($this->customer->getIdCustomer()),
        );

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemTransfer);
        $this->assertEmpty($wishlistItemTransfer->getIdWishlistItem());
        $this->assertNull($wishlistTransfer);
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotThrowExceptionWhenItemAlreadyExists(): void
    {
        // Arrange
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        // Act
        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->addItem($wishlistItemUpdateRequestTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenItemIsAlreadyRemoved(): void
    {
        // Arrange
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->filterBySku($this->product_1->getSku())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku())
            ->setIdWishlistItem($this->wishlistItem_1->getIdWishlistItem());

        // Act
        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenListIsEmpty(): void
    {
        // Arrange
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku())
            ->setIdWishlistItem($this->wishlistItem_1->getIdWishlistItem());

        // Act
        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldRemoveItem(): void
    {
        // Arrange
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku())
            ->setIdWishlistItem($this->wishlistItem_1->getIdWishlistItem());

        // Act
        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        // Assert
        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testCreateWishlistShouldCreateWishlist(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName('foo')
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistTransfer = $this->wishlistFacade->createWishlist($wishlistTransfer);

        // Assert
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertWishlistCount(2);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testValidateAndCreateWishlistShouldCreateWishlist(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName('foo')
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndCreateWishlist($wishlistTransfer);

        // Assert
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
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndCreateWishlist($wishlistTransfer);

        // Assert
        $this->assertFalse($wishlistTransferResponseTransfer->getIsSuccess());
        $this->assertCount(1, $wishlistTransferResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateWishlistShouldUpdateWishlist(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true,
        );

        $wishlistTransfer->setName('new name');

        // Act
        $wishlistTransfer = $this->wishlistFacade->updateWishlist($wishlistTransfer);

        // Assert
        $this->assertSame('new name', $wishlistTransfer->getName());
        $this->assertSame($this->wishlist->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertWishlistItemCount(2, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testValidateAndUpdateWishlistShouldUpdateWishlist(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true,
        );

        $wishlistTransfer->setName('new name');

        // Act
        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        // Assert
        $this->assertTrue($wishlistTransferResponseTransfer->getIsSuccess());

        $wishlistTransfer = $wishlistTransferResponseTransfer->getWishlist();
        $this->assertSame('new name', $wishlistTransfer->getName());
        $this->assertSame($this->wishlist->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertWishlistItemCount(2, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testValidateAndUpdateWishlistShouldFailWhenNameIsNotUnique(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();

        $newWishlistId = $this->wishlist->getIdWishlist() + 1;

        $wishlistTransfer
            ->setIdWishlist($newWishlistId)
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        // Assert
        $this->assertFalse($wishlistTransferResponseTransfer->getIsSuccess());
        $this->assertCount(1, $wishlistTransferResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testRemoveWishlistShouldRemoveItemsAsWell(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true,
        );

        // Act
        $wishlistTransfer = $this->wishlistFacade->removeWishlist($wishlistTransfer);

        // Assert
        $this->assertWishlistCount(0);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testRemoveWishlistByNameShouldRemoveItemsAsWell(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistTransfer = $this->wishlistFacade->removeWishlistByName($wishlistTransfer);

        // Assert
        $this->assertWishlistCount(0);
        $this->assertWishlistItemCount(0, $wishlistTransfer->getIdWishlist());
    }

    /**
     * @return void
     */
    public function testEmptyWishlistShouldRemoveItems(): void
    {
        // Arrange
        $wishlistTransfer = new WishlistTransfer();
        $wishlistTransfer->fromArray(
            $this->wishlist->toArray(),
            true,
        );

        // Act
        $this->wishlistFacade->emptyWishlist($wishlistTransfer);

        // Assert
        $this->assertWishlistCount(1);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testAddItemCollectionShouldAddItemCollection(): void
    {
        // Arrange
        $this->removeItemsFromWishlist();
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

        // Act
        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        // Assert
        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testRemoveItemCollectionShouldRemoveOnlySelectedItems(): void
    {
        // Arrange
        $this->removeItemsFromWishlist();
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

        // Act
        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        $this->assertWishlistItemCount(3);

        $wishlistItemCollectionTransfer = new WishlistItemCollectionTransfer();
        $wishlistItemCollectionTransfer
            ->addItem($wishlistItemTransfer_1)
            ->addItem($wishlistItemTransfer_2);

        $this->wishlistFacade->removeItemCollection($wishlistItemCollectionTransfer);

        // Assert
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testGetWishlistOverviewShouldReturnPaginatedResult(): void
    {
        // Arrange
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

        // Act
        $wishlistOverviewResponse = $this->wishlistFacade->getWishlistOverview($wishlistOverviewRequest);

        // Assert
        $this->assertInstanceOf(WishlistOverviewResponseTransfer::class, $wishlistOverviewResponse);
        $this->assertSame($this->wishlist->getName(), $wishlistOverviewResponse->getWishlist()->getName());
        $this->assertSame($pageNumber, $wishlistOverviewResponse->getPagination()->getPage());
        $this->assertSame($itemsPerPage, $wishlistOverviewResponse->getPagination()->getItemsPerPage());
        $this->assertSame($itemsTotal, $wishlistOverviewResponse->getPagination()->getItemsTotal());
        $this->assertSame(27, $wishlistOverviewResponse->getWishlist()->getNumberOfItems());
        $this->assertCount(7, $wishlistOverviewResponse->getWishlist()->getWishlistItems());
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistCollectionReturnsPersistedWishlistsByCustomerReference(): void
    {
        // Arrange
        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);

        // Act
        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($this->customer);

        // Assert
        $this->assertCount(3, $wishlistCollectionTransfer->getWishlists(), 'Customer wishlist collection should contain expected number of wishlists.');
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistCollectionReturnsPersistedWishlistsByCustomerId(): void
    {
        // Arrange
        $this->customer->setCustomerReference(null);

        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);

        // Act
        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($this->customer);

        // Assert
        $this->assertCount(3, $wishlistCollectionTransfer->getWishlists(), 'Customer wishlist collection should contain expected number of wishlists.');
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistCollectionReturnsPersistedWishlistsWithItemsByCustomerReference(): void
    {
        // Arrange
        $wishlistTransfer = $this->tester->haveWishlist([WishlistTransfer::FK_CUSTOMER => $this->customer->getIdCustomer()]);
        $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $this->customer->getIdCustomer(),
            WishlistItemTransfer::SKU => $this->product_1->getSku(),
            WishlistItemTransfer::WISHLIST_NAME => $wishlistTransfer->getName(),
        ]);

        // Act
        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($this->customer);

        // Assert
        foreach ($wishlistCollectionTransfer->getWishlists() as $foundWishlistTransfer) {
            if ($foundWishlistTransfer->getIdWishlist() === $wishlistTransfer->getIdWishlist()) {
                $this->assertWishlistItemCount(1, $wishlistTransfer->getIdWishlist());
            }
        }
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistCollectionReturnsPersistedWishlistsWithItemsByCustomerId(): void
    {
        // Arrange
        $this->customer->setCustomerReference(null);

        // Act
        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($this->customer);

        // Assert
        /** @var \Generated\Shared\Transfer\WishlistTransfer $wishlistTransferActual */
        $wishlistTransferActual = $wishlistCollectionTransfer->getWishlists()->offsetGet(0);

        $this->assertSame(2, $wishlistTransferActual->getNumberOfItems(), 'Customer wishlist should contain expected number of wishlist items.');
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistCollectionEnsureThatNumberOfItemsIsZero(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $this->tester->haveWishlist([
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        // Act
        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($customerTransfer);

        // Assert
        $this->assertSame(
            0,
            $wishlistCollectionTransfer->getWishlists()->offsetGet(0)->getNumberOfItems(),
            'Customer wishlist should contain expected number of wishlist items.',
        );
    }

    /**
     * @return void
     */
    public function testGetWishlistByFilterShouldReturnWishlistByName(): void
    {
        // Arrange
        $wishlistFilterTransfer = (new WishlistFilterTransfer())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        // Act
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter($wishlistFilterTransfer);

        // Assert
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess(), 'Wishlist response is unsuccessful.');
        $this->assertEmpty($wishlistResponseTransfer->getErrors(), 'Unexpected errors returned in response.');
        $this->assertNull($wishlistResponseTransfer->getErrorIdentifier(), 'Error identifier is supposed to be empty.');
        $this->assertNotNull($wishlistResponseTransfer->getWishlist(), 'No wishlist returned.');
        $this->assertSame($this->wishlist->getName(), $wishlistResponseTransfer->getWishlist()->getName(), 'Wishlist name is different.');
        $this->assertCount(2, $wishlistResponseTransfer->getWishlist()->getWishlistItems(), 'Returned wishlist items amount is not expected.');
        $this->assertSame(2, $wishlistResponseTransfer->getWishlist()->getNumberOfItems(), 'Wishlist numberOfItems is not as expected.');
        $this->assertSame($this->product_1->getSku(), $wishlistResponseTransfer->getWishlist()->getWishlistItems()[0]->getSku(), 'Wishlist item sku is unexpected.');
    }

    /**
     * @return void
     */
    public function testGetWishlistByFilterShouldReturnErrorInCaseWishlistIsNotFoundByName(): void
    {
        // Arrange
        $wishlistFilterTransfer = (new WishlistFilterTransfer())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setName('fake-name');

        // Act
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter($wishlistFilterTransfer);

        // Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess(), 'Wishlist response should be unsuccessful.');
        $this->assertCount(1, $wishlistResponseTransfer->getErrors(), 'Exactly 1 error is expected');
        $this->assertNull($wishlistResponseTransfer->getErrorIdentifier(), 'Error identifier is supposed to be empty.');
        $this->assertNull($wishlistResponseTransfer->getWishlist(), 'No wishlist should be returned.');
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemBeforeCreationReturnsSuccessfullResponseIfWishlistExists(): void
    {
        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->wishlistFacade->validateWishlistItemBeforeCreation($this->wishlistItem_1);

        // Assert
        $this->assertInstanceOf(WishlistPreAddItemCheckResponseTransfer::class, $wishlistPreAddItemCheckResponseTransfer);
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemBeforeCreationtReturnsSuccessfullResponseIfWishlistHasDefaultName(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName(static::DEFAULT_WISHLIST_NAME)
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->wishlistFacade->validateWishlistItemBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(WishlistPreAddItemCheckResponseTransfer::class, $wishlistPreAddItemCheckResponseTransfer);
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemBeforeCreationReturnsSuccessfullResponseIfWishlistNameIsAnEmptyString(): void
    {
        // Arrange
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName('')
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->wishlistFacade->validateWishlistItemBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(WishlistPreAddItemCheckResponseTransfer::class, $wishlistPreAddItemCheckResponseTransfer);
        $this->assertTrue($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemBeforeCreationReturnsNotSuccessfullResponseIfWishlistDoesNotExist(): void
    {
        // Arrange
        $wishlistResponseTransfer = $this->wishlistFacade->getWishlistByFilter(
            (new WishlistFilterTransfer())
                ->setName(static::NON_EXISTING_WISHLIST_NAME)
                ->setIdCustomer($this->customer->getIdCustomer()),
        );
        if ($wishlistResponseTransfer->getIsSuccess()) {
            $this->wishlistFacade->removeWishlist($wishlistResponseTransfer->getWishlist());
        }
        $wishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName(static::NON_EXISTING_WISHLIST_NAME)
            ->setFkCustomer($this->customer->getIdCustomer());

        // Act
        $wishlistPreAddItemCheckResponseTransfer = $this->wishlistFacade->validateWishlistItemBeforeCreation($wishlistItemTransfer);

        // Assert
        $this->assertInstanceOf(WishlistPreAddItemCheckResponseTransfer::class, $wishlistPreAddItemCheckResponseTransfer);
        $this->assertFalse($wishlistPreAddItemCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @param int $expected
     * @param int|null $idWishlist
     *
     * @return void
     */
    protected function assertWishlistItemCount(int $expected, ?int $idWishlist = null): void
    {
        if (!$idWishlist) {
            $idWishlist = $this->wishlist->getIdWishlist();
        }

        $count = $this->wishlistQueryContainer
            ->queryItemsByWishlistId($idWishlist)
            ->count();

        $this->assertSame($expected, $count);
    }

    /**
     * @param int $expected
     *
     * @return void
     */
    protected function assertWishlistCount(int $expected): void
    {
        $count = $this->wishlistQueryContainer
            ->queryWishlist()
            ->filterByFkCustomer($this->customer->getIdCustomer())
            ->count();

        $this->assertSame($expected, $count);
    }

    /**
     * @return void
     */
    protected function removeItemsFromWishlist(): void
    {
        $this->wishlistQueryContainer
            ->queryWishlistItem()
            ->filterByFkWishlist($this->wishlist->getIdWishlist())
            ->deleteAll();
    }
}
