<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacade;
use Spryker\Zed\ShoppingList\Business\ShoppingListFacade;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiBusinessFactory;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToCompanyUserFacadeBridge;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeBridge;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShoppingListsRestApi
 * @group Business
 * @group Facade
 * @group ShoppingListsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ShoppingListsRestApiFacadeTest extends Unit
{
    protected const COMPANY_USER_ID = 123;
    protected const COMPANY_USER_UUID = 'COMPANY_USER_UUID';
    protected const OWNER_NAME = 'John Doe';

    protected const GOOD_CUSTOMER_REFERENCE = 'GOOD_CUSTOMER_REFERENCE';
    protected const BAD_CUSTOMER_REFERENCE = 'BAD_CUSTOMER_REFERENCE';
    protected const GOOD_SHOPPING_LIST_UUID = 'GOOD_SHOPPING_LIST_UUID';
    protected const BAD_SHOPPING_LIST_UUID = 'BAD_SHOPPING_LIST_UUID';
    protected const GOOD_SHOPPING_LIST_NAME = 'GOOD_SHOPPING_LIST_NAME';
    protected const BAD_SHOPPING_LIST_NAME = 'BAD_SHOPPING_LIST_NAME';

    /**
     * @var \SprykerTest\Zed\ShoppingListsRestApi\ShoppingListsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var string|null
     */
    protected $lastShoppingListName;

    /**
     * @inheritdoc
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
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setUuid(static::COMPANY_USER_UUID)
            );
        $shoppingListCollectionTransfer = $shoppingListsRestApiFacade->getCustomerShoppingListCollection($customerTransfer);

        $this->assertCount(2, $shoppingListCollectionTransfer->getShoppingLists());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillGetCustomerShoppingListCollectionWithEmptyResult(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::BAD_CUSTOMER_REFERENCE)
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setUuid(static::COMPANY_USER_UUID)
            );
        $shoppingListCollectionTransfer = $shoppingListsRestApiFacade->getCustomerShoppingListCollection($customerTransfer);

        $this->assertCount(0, $shoppingListCollectionTransfer->getShoppingLists());
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillGetCustomerShoppingListCollectionWithoutCompanyUser(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE);
        $shoppingListsRestApiFacade->getCustomerShoppingListCollection($customerTransfer);
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillFindShoppingListByUuid(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->findShoppingListByUuid($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals(
            static::GOOD_SHOPPING_LIST_UUID,
            $shoppingListResponseTransfer->getShoppingList()->getUuid()
        );
        $this->assertEquals(static::OWNER_NAME, $shoppingListResponseTransfer->getShoppingList()->getOwner());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillFindShoppingListByUuidThatDoesNotExist(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::BAD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->findShoppingListByUuid($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillFindShoppingListByUuidWithoutCompanyUser(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListsRestApiFacade->findShoppingListByUuid($restShoppingListRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillCreateShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::GOOD_SHOPPING_LIST_NAME)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->createShoppingList($restShoppingListRequestTransfer);

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
    public function testShoppingListsRestApiFacadeWillCreateShoppingListWhenNameAlreadyExists(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::BAD_SHOPPING_LIST_NAME)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->createShoppingList($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillCreateShoppingListWithoutName(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(new ShoppingListTransfer());
        $shoppingListsRestApiFacade->createShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillCreateShoppingListWithoutCompanyUser(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::GOOD_SHOPPING_LIST_NAME)
            );
        $shoppingListsRestApiFacade->createShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::GOOD_SHOPPING_LIST_NAME)
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($restShoppingListRequestTransfer);

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
    public function testShoppingListsRestApiFacadeWillUpdateShoppingListWhenNameAlreadyExists(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::BAD_SHOPPING_LIST_NAME)
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_DUPLICATE_NAME,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingListThatDoesNotExist(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::GOOD_SHOPPING_LIST_NAME)
                    ->setUuid(static::BAD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->updateShoppingList($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingListWithoutName(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListsRestApiFacade->updateShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillUpdateShoppingListWithoutCompanyUser(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setName(static::GOOD_SHOPPING_LIST_NAME)
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListsRestApiFacade->updateShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillDeleteShoppingList(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingList($restShoppingListRequestTransfer);

        $this->assertTrue($shoppingListResponseTransfer->getIsSuccess());
        $this->assertNull($shoppingListResponseTransfer->getShoppingList());
    }

    /**
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillDeleteShoppingListThatDoesNotExist(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setCompanyUserUuid(static::COMPANY_USER_UUID)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::BAD_SHOPPING_LIST_UUID)
            );
        $shoppingListResponseTransfer = $shoppingListsRestApiFacade->deleteShoppingList($restShoppingListRequestTransfer);

        $this->assertFalse($shoppingListResponseTransfer->getIsSuccess());
        $this->assertEquals([
            SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND,
        ], $shoppingListResponseTransfer->getErrors());
    }

    /**
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return void
     */
    public function testShoppingListsRestApiFacadeWillDeleteShoppingListWithoutCompanyUser(): void
    {
        /** @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade $shoppingListsRestApiFacade */
        $shoppingListsRestApiFacade = $this->tester->getFacade();
        $shoppingListsRestApiFacade->setFactory($this->getMockShoppingListsRestApiFactory());

        $restShoppingListRequestTransfer = (new RestShoppingListRequestTransfer())
            ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            ->setShoppingList(
                (new ShoppingListTransfer())
                    ->setUuid(static::GOOD_SHOPPING_LIST_UUID)
            );
        $shoppingListsRestApiFacade->deleteShoppingList($restShoppingListRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShoppingListsRestApiFactory(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            ShoppingListsRestApiBusinessFactory::class,
            [
                'getShoppingListFacade',
                'getCompanyUserFacade',
            ]
        );

        $mockFactory->method('getShoppingListFacade')
            ->willReturn(new ShoppingListsRestApiToShoppingListFacadeBridge($this->getMockShoppingListFacade()));

        $mockFactory->method('getCompanyUserFacade')
            ->willReturn(new ShoppingListsRestApiToCompanyUserFacadeBridge($this->getMockCompanyUserFacade()));

        return $mockFactory;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockShoppingListFacade(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            ShoppingListFacade::class,
            [
                'getCustomerShoppingListCollection',
                'findShoppingListByUuid',
                'createShoppingList',
                'updateShoppingList',
                'removeShoppingList',
            ]
        );

        $mockCustomerFacade->method('getCustomerShoppingListCollection')
            ->willReturnCallback([$this, '_getCustomerShoppingListCollection']);

        $mockCustomerFacade->method('findShoppingListByUuid')
            ->willReturnCallback([$this, '_findShoppingListByUuid']);

        $mockCustomerFacade->method('createShoppingList')
            ->willReturnCallback([$this, '_createShoppingList']);

        $mockCustomerFacade->method('updateShoppingList')
            ->willReturnCallback([$this, '_updateShoppingList']);

        $mockCustomerFacade->method('removeShoppingList')
            ->willReturnCallback([$this, '_removeShoppingList']);

        return $mockCustomerFacade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMockCompanyUserFacade(): MockObject
    {
        $mockCustomerFacade = $this->createPartialMock(
            CompanyUserFacade::class,
            [
                'findCompanyUserByUuid',
            ]
        );

        $mockCustomerFacade->method('findCompanyUserByUuid')
            ->willReturnCallback([$this, '_findCompanyUserByUuid']);

        return $mockCustomerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function _findCompanyUserByUuid(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer
    {
        if ($companyUserTransfer->getUuid() !== static::COMPANY_USER_UUID) {
            return null;
        }

        return (new CompanyUserTransfer())
            ->setUuid(static::COMPANY_USER_UUID)
            ->setIdCompanyUser(static::COMPANY_USER_ID)
            ->setCustomer(
                (new CustomerTransfer())
                    ->setCustomerReference(static::GOOD_CUSTOMER_REFERENCE)
            );
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
                ->setOwner(static::OWNER_NAME) // Owner is defined only in read methods
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

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }
}
