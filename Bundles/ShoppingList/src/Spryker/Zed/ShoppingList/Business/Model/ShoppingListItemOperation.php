<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemOperation implements ShoppingListItemOperationInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface
     */
    protected $shoppingListResolver;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface
     */
    protected $shoppingListItemOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface $shoppingListResolver
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemOperationValidatorInterface $shoppingListItemOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListResolverInterface $shoppingListResolver,
        ShoppingListItemOperationValidatorInterface $shoppingListItemOperationValidator,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListResolver = $shoppingListResolver;
        $this->shoppingListItemOperationValidator = $shoppingListItemOperationValidator;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->addShoppingListItem($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer
            ->requireQuantity()
            ->requireSku();

        $shoppingListTransfer = $this->createShoppingListTransfer($shoppingListItemTransfer);
        $shoppingListTransfer = $this->resolveShoppingList($shoppingListTransfer);
        $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();
        $validatedShoppingListItemResponseTransfer = $this->shoppingListItemOperationValidator->validateItemAddRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$validatedShoppingListItemResponseTransfer->getIsSuccess()) {
            return $shoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransfer = $this->saveShoppingListItemTransaction($shoppingListItemTransfer);

        return $this->shoppingListItemOperationValidator->invalidateItemAddResponse($shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListItemCollectionTransfer = $this->shoppingListRepository
            ->findShoppingListItemsByIdShoppingList($shoppingListTransfer->getIdShoppingList());

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $this->deleteShoppingListItem($shoppingListItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function addItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListTransfer
            ->requireIdCompanyUser()
            ->requireCustomerReference();

        return $this->executeAddItemsTransaction($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    protected function executeAddItemsTransaction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            $shoppingListTransfer = $this->resolveShoppingList($shoppingListTransfer);
            $shoppingListTransfer = $this->sanitizeItems($shoppingListTransfer);

            $validatedShoppingListResponseTransfer = $this->shoppingListItemOperationValidator->validateItemAddBulkRequest(
                $shoppingListTransfer,
                new ShoppingListResponseTransfer()
            );
            if (!$validatedShoppingListResponseTransfer->getIsSuccess()) {
                return $validatedShoppingListResponseTransfer;
            }

            $shoppingListTransfer = $this->createItems($shoppingListTransfer);

            return (new ShoppingListResponseTransfer())
                ->setIsSuccess(true)
                ->setShoppingList($shoppingListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function sanitizeItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());
        }

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $updatedShoppingListItemTransfer = [];
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer = $this->shoppingListEntityManager->saveShoppingListItem($shoppingListItemTransfer);

            $updatedShoppingListItemTransfer[] = $shoppingListItemTransfer;

            $this->shoppingListItemOperationValidator->invalidateItemAddResponse(
                (new ShoppingListItemResponseTransfer())
                    ->setShoppingListItem($shoppingListItemTransfer)
                    ->setIsSuccess(true)
            );
        }

        return $shoppingListTransfer->setItems(new ArrayObject($updatedShoppingListItemTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer
            ->requireIdShoppingListItem()
            ->requireFkShoppingList();

        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        $validatedShoppingListItemResponseTransfer = $this->shoppingListItemOperationValidator->validateItemDeleteRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$validatedShoppingListItemResponseTransfer->getIsSuccess()) {
            return $shoppingListItemResponseTransfer;
        }

        return $this->deleteShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->updateShoppingListItem($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemTransfer
            ->requireIdShoppingListItem()
            ->requireFkShoppingList()
            ->requireQuantity();

        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        $validatedShoppingListItemResponseTransfer = $this->shoppingListItemOperationValidator->validateItemUpdateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$validatedShoppingListItemResponseTransfer->getIsSuccess()) {
            return $validatedShoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransfer = $this->saveShoppingListItemTransaction($shoppingListItemTransfer);

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItemWithoutPermissionsCheck(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        return $this->saveShoppingListItemTransaction($shoppingListItemTransfer)->getShoppingListItem() ?? $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function deleteShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer = $this->pluginExecutor->executeItemExpanderPlugins($shoppingListItemTransfer);
        $shoppingListItemTransfer = $this
            ->executeShoppingListItemCollectionExpanderPluginsForSingleItemTransfer($shoppingListItemTransfer);

        return $this->deleteShoppingListItemTransaction($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected function executeShoppingListItemCollectionExpanderPluginsForSingleItemTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->addItem($shoppingListItemTransfer);

        $shoppingListItemCollectionTransfer = $this->pluginExecutor
            ->executeShoppingListItemCollectionExpanderPlugins($shoppingListItemCollectionTransfer);

        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
        $shoppingListItemTransfer = $shoppingListItemCollectionTransfer->getItems()->getIterator()->current();

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function saveShoppingListItemTransaction(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            $shoppingListItemTransfer = $this->shoppingListEntityManager->saveShoppingListItem($shoppingListItemTransfer);
            $this->pluginExecutor->executePostSavePlugins($shoppingListItemTransfer);
            $this->pluginExecutor->executeBulkPostSavePlugins(
                (new ShoppingListItemCollectionTransfer())->addItem($shoppingListItemTransfer)
            );

            return (new ShoppingListItemResponseTransfer())
                ->setShoppingListItem($shoppingListItemTransfer)
                ->setIsSuccess(true);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function deleteShoppingListItemTransaction(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            $this->pluginExecutor->executeBeforeDeletePlugins($shoppingListItemTransfer);
            $this->shoppingListEntityManager->deleteShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

            return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createShoppingListTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListTransfer
    {
        return (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setCustomerReference($shoppingListItemTransfer->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function resolveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        if (!$shoppingListTransfer->getIdShoppingList()) {
            return $this->shoppingListResolver
                ->createDefaultShoppingListIfNotExists($shoppingListTransfer->getCustomerReference())
                ->setIdCompanyUser($shoppingListTransfer->getIdCompanyUser())
                ->setItems($shoppingListTransfer->getItems());
        }

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function saveShoppingListItemBulk(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemCollectionTransfer, $shoppingListTransfer) {
            $this->saveShoppingListItemsCollectionTransaction($shoppingListItemCollectionTransfer, $shoppingListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function saveShoppingListItemsCollectionTransaction(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer,
        ShoppingListTransfer $shoppingListTransfer
    ): void {
        $shoppingListItemCollectionTransfer = $this->shoppingListEntityManager->saveShoppingListItems($shoppingListItemCollectionTransfer, $shoppingListTransfer);
        $this->pluginExecutor->executeBulkPostSavePlugins($shoppingListItemCollectionTransfer);
    }
}
