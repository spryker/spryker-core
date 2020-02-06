<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemAddOperationValidatorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemDeleteOperationValidatorInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemUpdateOperationValidatorInterface;
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
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemAddOperationValidatorInterface
     */
    protected $addOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemUpdateOperationValidatorInterface
     */
    protected $updateOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemDeleteOperationValidatorInterface
     */
    protected $deleteOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemAddOperationValidatorInterface $addOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemUpdateOperationValidatorInterface $updateOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemDeleteOperationValidatorInterface $deleteOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListItemAddOperationValidatorInterface $addOperationValidator,
        ShoppingListItemUpdateOperationValidatorInterface $updateOperationValidator,
        ShoppingListItemDeleteOperationValidatorInterface $deleteOperationValidator,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->addOperationValidator = $addOperationValidator;
        $this->updateOperationValidator = $updateOperationValidator;
        $this->deleteOperationValidator = $deleteOperationValidator;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->addOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransfer = $this->saveShoppingListItemTransaction($shoppingListItemTransfer);

        return $this->addOperationValidator->invalidateResponse($shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->updateOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransfer = $this->saveShoppingListItemTransaction($shoppingListItemTransfer);

        return $this->updateOperationValidator->invalidateResponse(
            $shoppingListItemResponseTransfer
        );
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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    public function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            $this->executeDeleteShoppingListItemsTransaction($shoppingListTransfer);
        });
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

        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();
        if (!$this->addOperationValidator->validateBulkRequest(
            $shoppingListTransfer,
            $shoppingListResponseTransfer
        )) {
            return $shoppingListResponseTransfer;
        }
        $shoppingListTransfer = $shoppingListResponseTransfer->getShoppingList() ?? $shoppingListTransfer;

        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            return $this->executeAddItemsTransaction($shoppingListTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    protected function executeAddItemsTransaction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListTransfer = $this->createItems($shoppingListTransfer);

        return (new ShoppingListResponseTransfer())
            ->setIsSuccess(true)
            ->setShoppingList($shoppingListTransfer);
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

            $this->addOperationValidator->invalidateResponse(
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
        $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();

        if (!$this->deleteOperationValidator->validateRequest(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        )) {
            return $shoppingListItemResponseTransfer;
        }

        return $this->deleteOperationValidator->invalidateResponse(
            $this->deleteShoppingListItem($shoppingListItemTransfer)
        );
    }

    /**
     * @deprecated Use ShoppingListItemOperationInterface::updateShoppingListItem instead. Will be removed with next major release.
     *
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

        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemTransfer) {
            return $this->deleteShoppingListItemTransaction($shoppingListItemTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function executeDeleteShoppingListItemsTransaction(ShoppingListTransfer $shoppingListTransfer): void
    {
        $shoppingListItemCollectionTransfer = $this->shoppingListRepository
            ->findShoppingListItemsByIdShoppingList($shoppingListTransfer->getIdShoppingList());

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $this->deleteShoppingListItem($shoppingListItemTransfer);
        }
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
        $this->pluginExecutor->executeBeforeDeletePlugins($shoppingListItemTransfer);
        $this->shoppingListEntityManager->deleteShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
    }
}
