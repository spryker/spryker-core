<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShoppingListsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @method \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListsRestApiFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShoppingListsRestApiBusinessTester extends Actor
{
    use _generated\ShoppingListsRestApiBusinessTesterActions;

    public const COMPANY_USER_ID = 123;
    public const OWNER_NAME = 'John Doe';
    public const SHOPPING_LIST_UUID = 'SHOPPING_LIST_UUID';
    public const SHOPPING_LIST_ID = 123;
    public const READ_ONLY_SHOPPING_LIST_ID = 456;
    public const SHOPPING_LIST_ITEM_UUID = 'SHOPPING_LIST_ITEM_UUID';
    public const SHOPPING_LIST_ITEM_ID = 123;
    public const GOOD_CUSTOMER_REFERENCE = 'GOOD_CUSTOMER_REFERENCE';
    public const BAD_CUSTOMER_REFERENCE = 'BAD_CUSTOMER_REFERENCE';
    public const GOOD_SHOPPING_LIST_UUID = 'GOOD_SHOPPING_LIST_UUID';
    public const BAD_SHOPPING_LIST_UUID = 'BAD_SHOPPING_LIST_UUID';
    public const READ_ONLY_SHOPPING_LIST_UUID = 'FOREIGN_SHOPPING_LIST_UUID';
    public const GOOD_SHOPPING_LIST_NAME = 'GOOD_SHOPPING_LIST_NAME';
    public const BAD_SHOPPING_LIST_NAME = 'BAD_SHOPPING_LIST_NAME';
    public const GOOD_SKU = '123_123';
    public const BAD_SKU = '123_124';
    public const GOOD_QUANTITY = 1;
    public const BAD_QUANTITY = 0;

    /**
     * @var string|null
     */
    protected $lastShoppingListName;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(
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
    public function findShoppingListByUuid(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
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
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
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
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getUuid() === static::BAD_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND);

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
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($shoppingListTransfer->getUuid() === static::BAD_SHOPPING_LIST_UUID) {
            $shoppingListResponseTransfer->setIsSuccess(false)
                ->addError(SharedShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND);

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
    public function addShoppingListItem(
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
    public function updateShoppingListItemById(
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
    public function removeItemById(
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
