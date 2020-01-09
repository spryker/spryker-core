<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacade;
use Spryker\Zed\ShoppingList\Business\ShoppingListFacade;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiBusinessFactory;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeBridge;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListsRestApi
 * @group Business
 * @group Facade
 * @group ShoppingListsRestApiFacadeTest
 *
 * Add your own group annotations below this line
 */
class ShoppingListsRestApiFacadeTest extends Unit
{
    protected const COMPANY_USER_ID = 123;
    protected const OWNER_NAME = 'John Doe';
    protected const SHOPPING_LIST_UUID = 'SHOPPING_LIST_UUID';
    protected const SHOPPING_LIST_ID = 123;
    protected const READ_ONLY_SHOPPING_LIST_ID = 456;
    protected const SHOPPING_LIST_ITEM_UUID = 'SHOPPING_LIST_ITEM_UUID';
    protected const SHOPPING_LIST_ITEM_ID = 123;
    protected const GOOD_CUSTOMER_REFERENCE = 'GOOD_CUSTOMER_REFERENCE';
    protected const BAD_CUSTOMER_REFERENCE = 'BAD_CUSTOMER_REFERENCE';
    protected const GOOD_SHOPPING_LIST_UUID = 'GOOD_SHOPPING_LIST_UUID';
    protected const BAD_SHOPPING_LIST_UUID = 'BAD_SHOPPING_LIST_UUID';
    protected const READ_ONLY_SHOPPING_LIST_UUID = 'FOREIGN_SHOPPING_LIST_UUID';
    protected const GOOD_SHOPPING_LIST_NAME = 'GOOD_SHOPPING_LIST_NAME';
    protected const BAD_SHOPPING_LIST_NAME = 'BAD_SHOPPING_LIST_NAME';
    protected const GOOD_SKU = '123_123';
    protected const BAD_SKU = '123_124';
    protected const GOOD_QUANTITY = 1;
    protected const BAD_QUANTITY = 0;

    /**
     * @var \SprykerTest\Zed\ShoppingListsRestApi\ShoppingListsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var string|null
     */
    protected $lastShoppingListName;

    /**
     * @inheritDoc
     */
    public function _before()
    {
        parent::_before();

        $this->lastShoppingListName = null;
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillGetCustomerShoppingListCollection(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );
        $restShoppingListCollectionResponseTransfer = $shoppingListsRestApiFacade->getCustomerShoppingListCollection($customerTransfer);

        $this->assertCount(2, $restShoppingListCollectionResponseTransfer->getShoppingLists());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillCreateShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setName(static::GOOD_SHOPPING_LIST_NAME);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->createShoppingList($shoppingListTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GOOD_SHOPPING_LIST_NAME,
            $shoppingListResponseTransfer->getShoppingList()->getName()
        );
        $this->assertEquals(static::OWNER_NAME, $shoppingListResponseTransfer->getShoppingList()->getOwner());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotCreateShoppingListWhenNameAlreadyExists(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setName(static::BAD_SHOPPING_LIST_NAME);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->createShoppingList($shoppingListTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setName(static::GOOD_SHOPPING_LIST_NAME)
            ->setUuid(static::GOOD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($shoppingListTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GOOD_SHOPPING_LIST_NAME,
            $shoppingListResponseTransfer->getShoppingList()->getName()
        );
        $this->assertEquals(
            static::GOOD_SHOPPING_LIST_UUID,
            $shoppingListResponseTransfer->getShoppingList()->getUuid()
        );
        $this->assertEquals(static::OWNER_NAME, $shoppingListResponseTransfer->getShoppingList()->getOwner());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotUpdateShoppingListWhenNameAlreadyExists(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setName(static::BAD_SHOPPING_LIST_NAME)
            ->setUuid(static::GOOD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($shoppingListTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotUpdateShoppingListThatDoesNotExist(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setName(static::GOOD_SHOPPING_LIST_NAME)
            ->setUuid(static::BAD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($shoppingListTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillDeleteShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setUuid(static::GOOD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingList($shoppingListTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertNull($shoppingListResponseTransfer->getShoppingList());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotDeleteShoppingListThatBelongToOtherCustomer(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setUuid(static::READ_ONLY_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingList($shoppingListTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotDeleteShoppingListThatDoesNotExist(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setUuid(static::BAD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingList($shoppingListTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillAddItemToShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::GOOD_SKU)
                    ->setQuantity(static::GOOD_QUANTITY)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->addShoppingListItem($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals(static::GOOD_SKU, $shoppingListItemResponseTransfer->getShoppingListItem()->getSku());
        $this->assertEquals(
            static::GOOD_QUANTITY,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getQuantity()
        );
        $this->assertEquals(
            static::SHOPPING_LIST_ITEM_UUID,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getUuid()
        );
        $this->assertEquals(
            static::SHOPPING_LIST_ITEM_ID,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getIdShoppingListItem()
        );
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotAddItemToShoppingListWithBadQuantity(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::GOOD_SKU)
                    ->setQuantity(static::BAD_QUANTITY)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->addShoppingListItem($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_WRONG_QUANTITY,
        ], $shoppingListItemResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotAddItemToShoppingListWithBadSku(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::BAD_SKU)
                    ->setQuantity(static::GOOD_QUANTITY)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->addShoppingListItem($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_PRODUCT_NOT_FOUND,
        ], $shoppingListItemResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotAddItemToShoppingListWithoutShoppingListItem(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID);

        $shoppingListsRestApiFacade->addShoppingListItem($restShoppingListRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingListItem(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::GOOD_SKU)
                    ->setQuantity(static::GOOD_QUANTITY)
                    ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->updateShoppingListItem($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GOOD_QUANTITY,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getQuantity()
        );
        $this->assertEquals(
            static::SHOPPING_LIST_ID,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getFkShoppingList()
        );
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotUpdateShoppingListItemWithBadShoppingListUuid(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::BAD_SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::GOOD_SKU)
                    ->setQuantity(static::GOOD_QUANTITY)
                    ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->updateShoppingListItem($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
        ], $shoppingListItemResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotUpdateShoppingListItemWithBadShoppingListItemUuid(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku(static::GOOD_SKU)
                    ->setQuantity(static::GOOD_QUANTITY)
                    ->setUuid(static::BAD_SHOPPING_LIST_UUID)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->updateShoppingListItem($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_NOT_FOUND,
        ], $shoppingListItemResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillRemoveItemFromShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingListItem($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillNotRemoveItemFromShoppingListWithoutWritePermission(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiBusinessFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid(static::READ_ONLY_SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser(static::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingListItem($restShoppingListRequestTransfer);
        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiBusinessFactory
     */
    protected function getMockShoppingListsRestApiBusinessFactory(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            ShoppingListsRestApiBusinessFactory::class,
            [
                'getShoppingListFacade',
            ]
        );

        $mockFactory->method('getShoppingListFacade')
            ->willReturn(new ShoppingListsRestApiToShoppingListFacadeBridge($this->getMockShoppingListFacade()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShoppingList\Business\ShoppingListFacade
     */
    protected function getMockShoppingListFacade(): MockObject
    {
        $mockShoppingListFacade = $this->createPartialMock(
            ShoppingListFacade::class,
            [
                'getCustomerShoppingListCollection',
                'findShoppingListByUuid',
                'createShoppingList',
                'updateShoppingList',
                'removeShoppingList',
                'addShoppingListItem',
                'updateShoppingListItemById',
                'removeItemById',
            ]
        );

        $mockShoppingListFacade->method('getCustomerShoppingListCollection')
            ->willReturnCallback([$this, '_getCustomerShoppingListCollection']);

        $mockShoppingListFacade->method('findShoppingListByUuid')
            ->willReturnCallback([$this, '_findShoppingListByUuid']);

        $mockShoppingListFacade->method('createShoppingList')
            ->willReturnCallback([$this, '_createShoppingList']);

        $mockShoppingListFacade->method('updateShoppingList')
            ->willReturnCallback([$this, '_updateShoppingList']);

        $mockShoppingListFacade->method('removeShoppingList')
            ->willReturnCallback([$this, '_removeShoppingList']);

        $mockShoppingListFacade->method('addShoppingListItem')
            ->willReturnCallback([$this, '_addShoppingListItem']);

        $mockShoppingListFacade->method('updateShoppingListItemById')
            ->willReturnCallback([$this, '_updateShoppingListItemById']);

        $mockShoppingListFacade->method('removeItemById')
            ->willReturnCallback([$this, '_removeItemById']);

        return $mockShoppingListFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCompanyUserFacade(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CompanyUserFacade::class,
            [
                'findActiveCompanyUserByUuid',
            ]
        );

        $mockCustomerFacade->method('findActiveCompanyUserByUuid')
            ->willReturnCallback([$this, '_findActiveCompanyUserByUuid']);

        return $mockCustomerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function _getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): ShoppingListCollectionTransfer {
        $shoppingListCollectionTransfer = new ShoppingListCollectionTransfer();

        if ($customerTransfer->getCustomerReference() === static::BAD_CUSTOMER_REFERENCE) {
            return $shoppingListCollectionTransfer;
        }

        $shoppingListCollectionTransfer->addShoppingList(
            (new ShoppingListTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setIdCompanyUser(static::COMPANY_USER_ID)
        );

        $shoppingListCollectionTransfer->addShoppingList(
            (new ShoppingListTransfer())
                ->setCustomerReference($customerTransfer->getCustomerReference())
                ->setIdCompanyUser(static::COMPANY_USER_ID)
        );

        return $shoppingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function _findShoppingListByUuid(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getUuid() === static::BAD_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false);

            return $shoppingListResponseTransfer;
        }

        $shoppingListResponseTransfer->setShoppingList(
            (new ShoppingListTransfer())
                ->setCustomerReference($shoppingListTransfer->getCustomerReference())
                ->setIdCompanyUser(static::COMPANY_USER_ID)
                ->setUuid($shoppingListTransfer->getUuid())
                ->setName($this->lastShoppingListName)
                // Owner is defined only in read methods
                ->setOwner(static::OWNER_NAME)
                ->setIdShoppingList(
                    $shoppingListTransfer->getUuid() === static::READ_ONLY_SHOPPING_LIST_UUID
                        ? static::READ_ONLY_SHOPPING_LIST_ID
                        : static::SHOPPING_LIST_ID
                )
                ->addItem(
                    (new ShoppingListItemTransfer())
                        ->setQuantity(static::GOOD_QUANTITY)
                        ->setSku(static::GOOD_SKU)
                        ->setIdShoppingListItem(static::SHOPPING_LIST_ITEM_ID)
                        ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
                )
        );

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function _createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getName() === static::BAD_SHOPPING_LIST_NAME) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(ShoppingListsRestApiConfig::DUPLICATE_NAME_SHOPPING_LIST);

            return $shoppingListResponseTransfer;
        }

        $shoppingListResponseTransfer->setShoppingList(
            (new ShoppingListTransfer())
                ->setCustomerReference($shoppingListTransfer->getCustomerReference())
                ->setIdCompanyUser(static::COMPANY_USER_ID)
                ->setName($shoppingListTransfer->getName())
        );

        $this->lastShoppingListName = $shoppingListTransfer->getName();

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function _updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getUuid() === static::BAD_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND);

            return $shoppingListResponseTransfer;
        }

        if ($shoppingListTransfer->getName() === static::BAD_SHOPPING_LIST_NAME) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(ShoppingListsRestApiConfig::DUPLICATE_NAME_SHOPPING_LIST);

            return $shoppingListResponseTransfer;
        }

        $shoppingListResponseTransfer->setShoppingList(
            (new ShoppingListTransfer())
                ->setCustomerReference($shoppingListTransfer->getCustomerReference())
                ->setIdCompanyUser(static::COMPANY_USER_ID)
                ->setName($shoppingListTransfer->getName())
                ->setUuid($shoppingListTransfer->getUuid())
        );

        $this->lastShoppingListName = $shoppingListTransfer->getName();

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function _removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getUuid() === static::BAD_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND);

            return $shoppingListResponseTransfer;
        }

        if ($shoppingListTransfer->getUuid() === static::READ_ONLY_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(ShoppingListsRestApiConfig::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_DELETE_FAILED);

            return $shoppingListResponseTransfer;
        }

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function _addShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemTransfer->getSku() === static::BAD_SKU) {
            return (new ShoppingListItemResponseTransfer())
                ->addError(ShoppingListsRestApiConfig::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND)
                ->setIsSuccess(false);
        }

        if ($shoppingListItemTransfer->getQuantity() <= static::BAD_QUANTITY) {
            return (new ShoppingListItemResponseTransfer())
                ->addError(ShoppingListsRestApiConfig::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);
        }

        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(true)
            ->setShoppingListItem(
                $shoppingListItemTransfer
                    ->setIdShoppingListItem(static::SHOPPING_LIST_ITEM_ID)
                    ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function _updateShoppingListItemById(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemTransfer->getFkShoppingList() === static::READ_ONLY_SHOPPING_LIST_ID) {
            return (new ShoppingListItemResponseTransfer())
                ->addError(ShoppingListsRestApiConfig::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
                ->setIsSuccess(false);
        }

        if ($shoppingListItemTransfer->getQuantity() <= static::BAD_QUANTITY) {
            return (new ShoppingListItemResponseTransfer())
                ->addError(ShoppingListsRestApiConfig::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);
        }

        return (new ShoppingListItemResponseTransfer())
            ->setIsSuccess(true)
            ->setShoppingListItem(
                $shoppingListItemTransfer->setFkShoppingList(static::SHOPPING_LIST_ID)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function _removeItemById(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemTransfer->getFkShoppingList() === static::READ_ONLY_SHOPPING_LIST_ID) {
            return (new ShoppingListItemResponseTransfer())
                ->addError(ShoppingListsRestApiConfig::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
                ->setIsSuccess(false);
        }

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
    }
}
