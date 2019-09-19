<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Wishlist\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistOverviewRequestTransfer;
use Generated\Shared\Transfer\WishlistOverviewResponseTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Wishlist\Persistence\Map\SpyWishlistItemTableMap;
use Orm\Zed\Wishlist\Persistence\SpyWishlist;
use Orm\Zed\Wishlist\Persistence\SpyWishlistItem;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Stock\Business\StockFacade;
use Spryker\Zed\Wishlist\Business\WishlistFacade;
use Spryker\Zed\Wishlist\Persistence\WishlistQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Wishlist
 * @group Business
 * @group Facade
 * @group WishlistFacadeTest
 * Add your own group annotations below this line
 */
class WishlistFacadeTest extends Unit
{
    public const DEFAULT_NAME = 'default';

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
     * @var \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected $customer_1;

    /**
     * @var \Orm\Zed\Wishlist\Persistence\SpyWishlist
     */
    protected $wishlist;

    /**
     * @var \Spryker\Zed\Stock\Business\StockFacade
     */
    protected $stockFacade;

    /**
     * @var \Generated\Shared\Transfer\TypeTransfer
     */
    protected $stockTypeTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->wishlistQueryContainer = new WishlistQueryContainer();
        $this->wishlistFacade = new WishlistFacade();
        $this->stockFacade = new StockFacade();

        $this->setupCustomer();
        $this->setupStockType();
        $this->setupProduct();
        $this->setupWishlist();
    }

    /**
     * @return void
     */
    protected function setupCustomer()
    {
        $this->customer = $this->createCustomer('customer_reference', 'email');
        $this->customer_1 = $this->createCustomer('customer_reference_1', 'email_1');
    }

    /**
     * @param string $reference
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function createCustomer(string $reference, string $email): SpyCustomer
    {
        $customerEntity = (new SpyCustomer())
            ->setCustomerReference($reference)
            ->setEmail($email);

        $customerEntity->save();

        return $customerEntity;
    }

    /**
     * @return void
     */
    protected function setupProduct()
    {
        $this->productAbstract = (new SpyProductAbstract())
            ->setSku('abstract_sku')
            ->setAttributes('{}');

        $this->productAbstract->save();

        $this->product_1 = $this->createProduct('concrete_sku_1', $this->productAbstract->getIdProductAbstract(), 10);
        $this->product_2 = $this->createProduct('concrete_sku_2', $this->productAbstract->getIdProductAbstract(), 20);
        $this->product_3 = $this->createProduct('concrete_sku_3', $this->productAbstract->getIdProductAbstract(), 30);
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     * @param int|null $quantity
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createProduct($sku, $idProductAbstract, $quantity = null)
    {
        $productEntity = (new SpyProduct())
            ->setSku($sku)
            ->setAttributes('{}')
            ->setFkProductAbstract($idProductAbstract);

        $productEntity->save();

        $this->createStockProduct($sku, $quantity);

        return $productEntity;
    }

    /**
     * @return void
     */
    protected function setupBigWishlist()
    {
        for ($a = 0; $a < 25; $a++) {
            $productEntity = $this->createProduct('concrete_sku_many_' . $a, $this->productAbstract->getIdProductAbstract());
            $this->createWishlistItem($this->wishlist->getIdWishlist(), $productEntity->getSku());
        }
    }

    /**
     * @param int $idWishlist
     * @param int $sku
     *
     * @return \Orm\Zed\Wishlist\Persistence\SpyWishlistItem
     */
    protected function createWishlistItem($idWishlist, $sku)
    {
        $wishlistItemEntity = (new SpyWishlistItem())
            ->setFkWishlist($idWishlist)
            ->setSku($sku);

        $wishlistItemEntity->save();

        return $wishlistItemEntity;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function setupWishlist($name = self::DEFAULT_NAME)
    {
        $this->setupEmptyWishlist($name);

        $this->createWishlistItem($this->wishlist->getIdWishlist(), $this->product_1->getSku());
        $this->createWishlistItem($this->wishlist->getIdWishlist(), $this->product_2->getSku());
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function setupEmptyWishlist(string $name = self::DEFAULT_NAME): void
    {
        $this->wishlist = (new SpyWishlist())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($name);

        $this->wishlist->save();
    }

    /**
     * @return void
     */
    public function testGetWishListByName()
    {
        $this->setupBigWishlist();

        $wishlistTransfer = (new WishlistTransfer())
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setName($this->wishlist->getName());

        $wishlistTransfer = $this->wishlistFacade->getWishlistByName($wishlistTransfer);

        $this->assertInstanceOf(WishlistTransfer::class, $wishlistTransfer);
        $this->assertEquals('default', $wishlistTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddItemShouldAddItem()
    {
        $WishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
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
    public function testAddNonExistingItemShouldSkipItem()
    {
        $WishlistItemTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku('non-existing-sku');

        $WishlistItemTransfer = $this->wishlistFacade->addItem($WishlistItemTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $WishlistItemTransfer);
        $this->assertEmpty($WishlistItemTransfer->getIdWishlistItem());
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotThrowExceptionWhenItemAlreadyExists()
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->addItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(2);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenItemIsAlreadyRemoved()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->filterBySku($this->product_1->getSku())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(1);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotThrowExceptionWhenListIsEmpty()
    {
        $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->delete();

        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
        $this->assertWishlistItemCount(0);
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldRemoveItem()
    {
        $wishlistItemUpdateRequestTransfer = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer())
            ->setSku($this->product_1->getSku());

        $wishlistItemUpdateRequestTransfer = $this->wishlistFacade->removeItem($wishlistItemUpdateRequestTransfer);

        $this->assertInstanceOf(WishlistItemTransfer::class, $wishlistItemUpdateRequestTransfer);
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
    public function testValidateAndCreateWishlistShouldCreateWishlist()
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
    public function testValidateAndCreateWishlistShouldFailWhenNameIsNotUnique()
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
     * @return void
     */
    public function testValidateAndUpdateWishlistShouldUpdateWishlist()
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
    public function testValidateAndUpdateWishlistShouldFailWhenNameIsNotUnique()
    {
        $wishlistTransfer = new WishlistTransfer();

        $newWhishListId = $this->wishlist->getIdWishlist() + 1;

        $wishlistTransfer
            ->setIdWishlist($newWhishListId)
            ->setName($this->wishlist->getName())
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistTransferResponseTransfer = $this->wishlistFacade->validateAndUpdateWishlist($wishlistTransfer);

        $this->assertFalse($wishlistTransferResponseTransfer->getIsSuccess());
        $this->assertCount(1, $wishlistTransferResponseTransfer->getErrors());
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
     * @return void
     */
    public function testRemoveWishlistByNameShouldRemoveItemsAsWell()
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
    public function testEmptyWishlistShouldRemoveItems()
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
    public function testAddItemCollectionShouldAddItemCollection()
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($this->wishlist->toArray(), true);

        $wishlistItemTransfer_1 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_1->getSku());

        $wishlistItemTransfer_2 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_2->getSku());

        $wishlistItemTransfer_3 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_3->getSku());

        $this->wishlistFacade->addItemCollection($wishlistTransfer, [$wishlistItemTransfer_1, $wishlistItemTransfer_2, $wishlistItemTransfer_3]);

        $this->assertWishlistItemCount(3);
    }

    /**
     * @return void
     */
    public function testRemoveItemCollectionShouldRemoveOnlySelectedItems()
    {
        $wishlistTransfer = (new WishlistTransfer())
            ->fromArray($this->wishlist->toArray(), true);

        $wishlistItemTransfer_1 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_1->getSku());

        $wishlistItemTransfer_2 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
            ->setSku($this->product_2->getSku());

        $wishlistItemTransfer_3 = (new WishlistItemTransfer())
            ->setWishlistName(self::DEFAULT_NAME)
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
    public function testGetWishlistOverviewShouldReturnPaginatedResult()
    {
        $this->setupBigWishlist();

        $pageNumber = 3;
        $itemsPerPage = 10;
        $orderBy = SpyWishlistItemTableMap::COL_CREATED_AT;
        $orderDirection = Criteria::DESC;
        $itemsTotal = $this->wishlistQueryContainer
            ->queryItemsByWishlistId($this->wishlist->getIdWishlist())
            ->count();

        $wishlistTransfer = (new WishlistTransfer())
            ->setName(self::DEFAULT_NAME)
            ->setFkCustomer($this->customer->getIdCustomer());

        $wishlistOverviewRequest = (new WishlistOverviewRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setPage($pageNumber)
            ->setItemsPerPage($itemsPerPage)
            ->setOrderBy($orderBy)
            ->setOrderDirection($orderDirection);

        $wishlistOverviewResponse = $this->wishlistFacade->getWishlistOverview($wishlistOverviewRequest);

        $this->assertInstanceOf(WishlistOverviewResponseTransfer::class, $wishlistOverviewResponse);
        $this->assertEquals('default', $wishlistTransfer->getName());
        $this->assertEquals($pageNumber, $wishlistOverviewResponse->getPagination()->getPage());
        $this->assertEquals($itemsPerPage, $wishlistOverviewResponse->getPagination()->getItemsPerPage());
        $this->assertEquals($itemsTotal, $wishlistOverviewResponse->getPagination()->getItemsTotal());
    }

    /**
     * @return void
     */
    public function testGetWishlistsByCustomerReturnPersistedWishlists()
    {
        $this->setupWishlist('test-wishlist-1');
        $this->setupWishlist('test-wishlist-2');

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($this->customer->toArray(), true);

        $wishlistCollectionTransfer = $this->wishlistFacade->getCustomerWishlistCollection($customerTransfer);

        $this->assertCount(3, $wishlistCollectionTransfer->getWishlists(), 'Customer wishlist collection should contain expected number of wishlists.');
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistByUuidShouldReturnWishlist(): void
    {
        //Arrange
        $wishlistName = 'Test Wishlist';

        $this->setupEmptyWishlist($wishlistName);
        $this->createWishlistItem($this->wishlist->getIdWishlist(), $this->product_1->getSku());

        //Act
        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid(
            (new WishlistRequestTransfer())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setUuid($this->wishlist->getUuid())
        );
        $wishlist = $wishlistResponseTransfer->getWishlist();

        //Assert
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess(), 'Wishlist response is unsuccessful.');
        $this->assertEmpty($wishlistResponseTransfer->getErrors(), 'Unexpected errors returned in response.');
        $this->assertNull($wishlistResponseTransfer->getErrorIdentifier(), 'Error identifier is supposed to be empty.');
        $this->assertNotNull($wishlist, 'No wishlist returned.');
        $this->assertEquals($wishlistName, $wishlist->getName(), 'Wishlist name is different.');
        $this->assertCount(1, $wishlist->getWishlistItems(), 'Returned wishlist items amount is not expected.');
        $this->assertEquals($this->product_1->getSku(), $wishlist->getWishlistItems()[0]->getSku(), 'Wishlist item sku is unexpected.');
    }

    /**
     * @return void
     */
    public function testGetCustomerWishlistByUuidShouldReturnError(): void
    {
        //Arrange
        $uuidWishlistNotExisting = 'fake-uuid';

        //Act
        $wishlistResponseTransfer = $this->wishlistFacade->getCustomerWishlistByUuid(
            (new WishlistRequestTransfer())
                ->setIdCustomer($this->customer->getIdCustomer())
                ->setUuid($uuidWishlistNotExisting)
        );

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

    /**
     * @return void
     */
    protected function setupStockType()
    {
        $this->stockTypeTransfer = new TypeTransfer();
        $this->stockTypeTransfer->setName('Test stock type');
        $this->stockTypeTransfer->setIdStock($this->stockFacade->createStockType($this->stockTypeTransfer));
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return void
     */
    protected function createStockProduct($sku, $quantity)
    {
        $stockProductTransfer = new StockProductTransfer();
        $stockProductTransfer
            ->setSku($sku)
            ->setQuantity($quantity)
            ->setStockType($this->stockTypeTransfer->getName());

        $this->stockFacade->createStockProduct($stockProductTransfer);
    }
}
