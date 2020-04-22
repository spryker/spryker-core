<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger;

use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;

class ShoppingListItemMessageAdder implements ShoppingListItemMessageAdderInterface
{
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS = 'customer.account.shopping_list.item.add.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED = 'customer.account.shopping_list.item.add.failed';

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
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingSuccessMessage(string $sku): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $sku])
        );
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function addShoppingListItemAddingFailedMessage(string $sku): void
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $sku])
        );
    }
}
