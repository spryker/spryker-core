<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacade;
use Spryker\Zed\ShoppingList\Business\ShoppingListFacade;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiBusinessFactory;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeBridge;

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
    /**
     * @var \SprykerTest\Zed\ShoppingListsRestApi\ShoppingListsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @inheritDoc
     */
    public function _before()
    {
        parent::_before();
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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setName($this->tester::GOOD_SHOPPING_LIST_NAME);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->createShoppingList($shoppingListTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $this->tester::GOOD_SHOPPING_LIST_NAME,
            $shoppingListResponseTransfer->getShoppingList()->getName()
        );
        $this->assertEquals($this->tester::OWNER_NAME, $shoppingListResponseTransfer->getShoppingList()->getOwner());
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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setName($this->tester::BAD_SHOPPING_LIST_NAME);

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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setName($this->tester::GOOD_SHOPPING_LIST_NAME)
            ->setUuid($this->tester::GOOD_SHOPPING_LIST_UUID);

        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($shoppingListTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $this->tester::GOOD_SHOPPING_LIST_NAME,
            $shoppingListResponseTransfer->getShoppingList()->getName()
        );
        $this->assertEquals(
            $this->tester::GOOD_SHOPPING_LIST_UUID,
            $shoppingListResponseTransfer->getShoppingList()->getUuid()
        );
        $this->assertEquals($this->tester::OWNER_NAME, $shoppingListResponseTransfer->getShoppingList()->getOwner());
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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setName($this->tester::BAD_SHOPPING_LIST_NAME)
            ->setUuid($this->tester::GOOD_SHOPPING_LIST_UUID);

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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setName($this->tester::GOOD_SHOPPING_LIST_NAME)
            ->setUuid($this->tester::BAD_SHOPPING_LIST_UUID);

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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setUuid($this->tester::GOOD_SHOPPING_LIST_UUID);

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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setUuid($this->tester::READ_ONLY_SHOPPING_LIST_UUID);

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
            ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
            ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            ->setUuid($this->tester::BAD_SHOPPING_LIST_UUID);

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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::GOOD_SKU)
                    ->setQuantity($this->tester::GOOD_QUANTITY)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->addShoppingListItem($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals($this->tester::GOOD_SKU, $shoppingListItemResponseTransfer->getShoppingListItem()->getSku());
        $this->assertEquals(
            $this->tester::GOOD_QUANTITY,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getQuantity()
        );
        $this->assertEquals(
            $this->tester::SHOPPING_LIST_ITEM_UUID,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getUuid()
        );
        $this->assertEquals(
            $this->tester::SHOPPING_LIST_ITEM_ID,
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::GOOD_SKU)
                    ->setQuantity($this->tester::BAD_QUANTITY)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::BAD_SKU)
                    ->setQuantity($this->tester::GOOD_QUANTITY)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID);

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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::GOOD_SKU)
                    ->setQuantity($this->tester::GOOD_QUANTITY)
                    ->setUuid($this->tester::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->updateShoppingListItem($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListItemResponseTransfer->getIsSuccess());
        $this->assertEquals(
            $this->tester::GOOD_QUANTITY,
            $shoppingListItemResponseTransfer->getShoppingListItem()->getQuantity()
        );
        $this->assertEquals(
            $this->tester::SHOPPING_LIST_ID,
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::BAD_SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::GOOD_SKU)
                    ->setQuantity($this->tester::GOOD_QUANTITY)
                    ->setUuid($this->tester::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setSku($this->tester::GOOD_SKU)
                    ->setQuantity($this->tester::GOOD_QUANTITY)
                    ->setUuid($this->tester::BAD_SHOPPING_LIST_UUID)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setUuid($this->tester::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
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

        $restShoppingListRequestTransfer = (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($this->tester::READ_ONLY_SHOPPING_LIST_UUID)
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setUuid($this->tester::SHOPPING_LIST_ITEM_UUID)
                    ->setCustomerReference($this->tester::GOOD_CUSTOMER_REFERENCE)
                    ->setIdCompanyUser($this->tester::COMPANY_USER_ID)
            );

        $shoppingListItemResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingListItem($restShoppingListRequestTransfer);
        $this->assertFalse($shoppingListItemResponseTransfer->getIsSuccess());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiBusinessFactory
     */
    protected function getMockShoppingListsRestApiBusinessFactory(): ShoppingListsRestApiBusinessFactory
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
    protected function getMockShoppingListFacade(): ShoppingListFacade
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
            ->willReturnCallback([$this->tester, 'getCustomerShoppingListCollection']);

        $mockShoppingListFacade->method('findShoppingListByUuid')
            ->willReturnCallback([$this->tester, 'findShoppingListByUuid']);

        $mockShoppingListFacade->method('createShoppingList')
            ->willReturnCallback([$this->tester, 'createShoppingList']);

        $mockShoppingListFacade->method('updateShoppingList')
            ->willReturnCallback([$this->tester, 'updateShoppingList']);

        $mockShoppingListFacade->method('removeShoppingList')
            ->willReturnCallback([$this->tester, 'removeShoppingList']);

        $mockShoppingListFacade->method('addShoppingListItem')
            ->willReturnCallback([$this->tester, 'addShoppingListItem']);

        $mockShoppingListFacade->method('updateShoppingListItemById')
            ->willReturnCallback([$this->tester, 'updateShoppingListItemById']);

        $mockShoppingListFacade->method('removeItemById')
            ->willReturnCallback([$this->tester, 'removeItemById']);

        return $mockShoppingListFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyUser\Business\CompanyUserFacade
     */
    protected function getMockCompanyUserFacade(): CompanyUserFacade
    {
        $mockCustomerFacade = $this->createPartialMock(
            CompanyUserFacade::class,
            [
                'findActiveCompanyUserByUuid',
            ]
        );

        $mockCustomerFacade->method('findActiveCompanyUserByUuid')
            ->willReturnCallback([$this->tester, 'findActiveCompanyUserByUuid']);

        return $mockCustomerFacade;
    }
}
