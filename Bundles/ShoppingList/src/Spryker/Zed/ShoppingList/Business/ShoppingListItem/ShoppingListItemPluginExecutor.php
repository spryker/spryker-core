<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem;

use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;

class ShoppingListItemPluginExecutor implements ShoppingListItemPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface[]
     */
    protected $beforeDeletePlugins;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemPostSavePluginInterface[]
     */
    protected $postSavePlugins;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface[]
     */
    protected $addItemPreCheckPlugins;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface[]
     */
    protected $itemCollectionExpanderPlugins;

    /**
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface[] $bulkPostSavePlugins
     */
    protected $bulkPostSavePlugins;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBeforeDeletePluginInterface[] $beforeDeletePlugins
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemPostSavePluginInterface[] $postSavePlugins
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface[] $addItemPreCheckPlugins
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[] $itemExpanderPlugins
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemCollectionExpanderPluginInterface[] $itemCollectionExpanderPlugins
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ShoppingListItemBulkPostSavePluginInterface[] $bulkPostSavePlugins
     */
    public function __construct(
        ShoppingListToMessengerFacadeInterface $messengerFacade,
        array $beforeDeletePlugins,
        array $postSavePlugins,
        array $addItemPreCheckPlugins,
        array $itemExpanderPlugins,
        array $itemCollectionExpanderPlugins,
        array $bulkPostSavePlugins
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->beforeDeletePlugins = $beforeDeletePlugins;
        $this->postSavePlugins = $postSavePlugins;
        $this->addItemPreCheckPlugins = $addItemPreCheckPlugins;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->itemCollectionExpanderPlugins = $itemCollectionExpanderPlugins;
        $this->bulkPostSavePlugins = $bulkPostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function executeBeforeDeletePlugins(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        foreach ($this->beforeDeletePlugins as $beforeDeletePlugin) {
            $shoppingListItemTransfer = $beforeDeletePlugin->execute($shoppingListItemTransfer);
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutor::executeBulkPostSavePlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function executePostSavePlugins(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        foreach ($this->postSavePlugins as $postSavePlugin) {
            $shoppingListItemTransfer = $postSavePlugin->execute($shoppingListItemTransfer);
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function executeBulkPostSavePlugins(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        foreach ($this->bulkPostSavePlugins as $bulkPostSavePlugin) {
            $shoppingListItemCollectionTransfer = $bulkPostSavePlugin->execute($shoppingListItemCollectionTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutor::executeShoppingListItemCollectionExpanderPlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function executeItemExpanderPlugins(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        foreach ($this->itemExpanderPlugins as $itemExpanderPlugin) {
            $shoppingListItemTransfer = $itemExpanderPlugin->expandItem($shoppingListItemTransfer);
        }

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function executeShoppingListItemCollectionExpanderPlugins(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        foreach ($this->itemCollectionExpanderPlugins as $itemCollectionExpanderPlugin) {
            $shoppingListItemCollectionTransfer = $itemCollectionExpanderPlugin
                ->expandItemCollection($shoppingListItemCollectionTransfer);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function executeAddShoppingListItemPreCheckPlugins(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        $commonShoppingListPreAddItemCheckResponseTransfer = (new ShoppingListPreAddItemCheckResponseTransfer())
            ->setIsSuccess(true);

        foreach ($this->addItemPreCheckPlugins as $preAddItemCheckPlugin) {
            $shoppingListPreAddItemCheckResponseTransfer = $preAddItemCheckPlugin->check($shoppingListItemTransfer);
            if (!$shoppingListPreAddItemCheckResponseTransfer->getIsSuccess()) {
                $this->processErrorMessages(
                    $shoppingListPreAddItemCheckResponseTransfer,
                    $commonShoppingListPreAddItemCheckResponseTransfer->setIsSuccess(false)
                );
            }
        }

        return $commonShoppingListPreAddItemCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return bool
     */
    public function executeAddItemPreCheckPlugins(ShoppingListItemTransfer $shoppingListItemTransfer): bool
    {
        return $this->executeAddShoppingListItemPreCheckPlugins($shoppingListItemTransfer)->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer $shoppingListPreAddItemCheckResponseTransfer
     * @param \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer $commonShoppingListPreAddItemCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    protected function processErrorMessages(
        ShoppingListPreAddItemCheckResponseTransfer $shoppingListPreAddItemCheckResponseTransfer,
        ShoppingListPreAddItemCheckResponseTransfer $commonShoppingListPreAddItemCheckResponseTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        foreach ($shoppingListPreAddItemCheckResponseTransfer->getMessages() as $messageTransfer) {
            $this->messengerFacade->addErrorMessage($messageTransfer);
            $commonShoppingListPreAddItemCheckResponseTransfer->addMessage($messageTransfer);
        }

        return $commonShoppingListPreAddItemCheckResponseTransfer;
    }
}
