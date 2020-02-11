<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;

class ShoppingListItemMessageAdder implements ShoppingListItemMessageAdderInterface
{
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_UPDATE_FAILED = 'customer.account.shopping_list.item.update.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_UPDATE_SUCCESS = 'customer.account.shopping_list.item.update.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS = 'customer.account.shopping_list.item.add.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED = 'customer.account.shopping_list.item.add.failed';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_DELETE_SUCCESS = 'customer.account.shopping_list.item.delete.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_DELETE_FAILED = 'customer.account.shopping_list.item.delete.failed';

    protected const GLOSSARY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(ShoppingListToMessengerFacadeInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @return void
     */
    public function addShoppingListItemUpdateFailedMessage(): void
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_UPDATE_FAILED)
        );
    }

    /**
     * @return void
     */
    public function addShoppingListItemUpdateSuccessMessage(): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_UPDATE_SUCCESS)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function addShoppingListItemAddingFailedMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $shoppingListItemTransfer->getSku()])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    public function addShoppingListItemAddingSuccessMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $shoppingListItemTransfer->getSku()])
        );
    }

    /**
     * @return void
     */
    public function addShoppingListItemDeleteFailedMessage(): void
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_DELETE_FAILED)
        );
    }

    /**
     * @return void
     */
    public function addShoppingListItemDeleteSuccessMessage(): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_DELETE_SUCCESS)
        );
    }
}
