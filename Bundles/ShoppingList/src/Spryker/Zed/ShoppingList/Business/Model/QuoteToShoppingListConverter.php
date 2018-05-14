<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;

class QuoteToShoppingListConverter implements QuoteToShoppingListConverterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface
     */
    protected $shoppingListResolver;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsExtractorExpanderPluginInterface[]
     */
    protected $quoteItemExpanderPlugins;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface $shoppingListResolver
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsExtractorExpanderPluginInterface[] $quoteItemExpanderPlugins
     */
    public function __construct(
        ShoppingListResolverInterface $shoppingListResolver,
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListToPersistentCartFacadeInterface $persistentCartFacade,
        array $quoteItemExpanderPlugins
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->shoppingListResolver = $shoppingListResolver;
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->quoteItemExpanderPlugins = $quoteItemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromQuote(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer
    {
        $shoppingListFromCartRequestTransfer->requireShoppingListName()->requireIdQuote();

        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListFromCartRequestTransfer) {
            return $this->executeCreateShoppingListFromQuoteTransaction($shoppingListFromCartRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function executeCreateShoppingListFromQuoteTransaction(ShoppingListFromCartRequestTransfer $shoppingListFromCartRequestTransfer): ShoppingListTransfer
    {
        $quoteResponseTransfer = $this->persistentCartFacade->findQuote(
            $shoppingListFromCartRequestTransfer->getIdQuote(),
            $shoppingListFromCartRequestTransfer->getCustomer()
        );

        $shoppingListTransfer = $this->shoppingListResolver->createShoppingListIfNotExists(
            $shoppingListFromCartRequestTransfer->getCustomer()->getCustomerReference(),
            $shoppingListFromCartRequestTransfer->getShoppingListName()
        );

        $itemTransferCollection = $this->getQuoteItems($quoteResponseTransfer->getQuoteTransfer());

        $this->createShoppingListItems($itemTransferCollection, $shoppingListTransfer);

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getQuoteItems(QuoteTransfer $quoteTransfer): array
    {
        $itemTransferCollection = (array)$quoteTransfer->getItems();

        foreach ($this->quoteItemExpanderPlugins as $expanderPlugin) {
            $itemTransferCollection = $expanderPlugin->expand($itemTransferCollection, $quoteTransfer);
        }

        return $itemTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransferCollection
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function createShoppingListItems(array $itemTransferCollection, ShoppingListTransfer $shoppingListTransfer): void
    {
        foreach ($itemTransferCollection as $item) {
            $shoppingListItemTransfer = (new ShoppingListItemTransfer())
                ->setFkShoppingList($shoppingListTransfer->getIdShoppingList())
                ->setQuantity($item->getQuantity())
                ->setSku($item->getSku());

            $this->shoppingListEntityManager->saveShoppingListItem($shoppingListItemTransfer);
        }
    }
}
