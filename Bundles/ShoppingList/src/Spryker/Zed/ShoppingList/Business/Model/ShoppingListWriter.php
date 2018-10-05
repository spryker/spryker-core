<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\ShoppingListEvents;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListWriter implements ShoppingListWriterInterface
{
    use TransactionTrait;

    use PermissionAwareTrait;

    protected const DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';
    protected const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperationInterface
     */
    protected $shoppingListItemOperation;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListReaderInterface
     */
    protected $shoppingListReader;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperationInterface $shoppingListItemOperation
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListReaderInterface $shoppingListReader
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToEventFacadeInterface $eventFacade,
        ShoppingListItemOperationInterface $shoppingListItemOperation,
        ShoppingListReaderInterface $shoppingListReader,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->eventFacade = $eventFacade;
        $this->shoppingListItemOperation = $shoppingListItemOperation;
        $this->shoppingListReader = $shoppingListReader;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function validateAndSaveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();
        $shoppingListResponseTransfer->setIsSuccess(false);

        if ($this->checkShoppingListWithSameName($shoppingListTransfer)) {
            $shoppingListResponseTransfer->addError(static::DUPLICATE_NAME_SHOPPING_LIST);

            return $shoppingListResponseTransfer;
        }

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListResponseTransfer->addError(static::CANNOT_UPDATE_SHOPPING_LIST);

            return $shoppingListResponseTransfer;
        }

        $shoppingListResponseTransfer->setIsSuccess(true);
        $shoppingListResponseTransfer->setShoppingList($this->saveShoppingList($shoppingListTransfer));

        return $shoppingListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);

        if (!$shoppingListTransfer || !$this->checkWritePermission($shoppingListTransfer)) {
            return (new ShoppingListResponseTransfer())->setIsSuccess(false);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($shoppingListTransfer) {
                return $this->executeRemoveShoppingListTransaction($shoppingListTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function saveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($shoppingListTransfer) {
                return $this->executeSaveShoppingListTransaction($shoppingListTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function clearShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListTransfer = $this->shoppingListReader->getShoppingList($shoppingListTransfer);

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            return (new ShoppingListResponseTransfer())->setIsSuccess(false);
        }

        return $this->deleteShoppingListItems($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    protected function deleteShoppingListItems(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            $this->executeDeleteShoppingListItemsTransaction($shoppingListTransfer);
        });

        return (new ShoppingListResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkShoppingListWithSameName(ShoppingListTransfer $shoppingListTransfer): bool
    {
        $foundShoppingListTransfer = $this->findCustomerShoppingListByName($shoppingListTransfer);

        return $foundShoppingListTransfer && ($foundShoppingListTransfer->getIdShoppingList() !== $shoppingListTransfer->getIdShoppingList());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    public function findCustomerShoppingListByName(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListTransfer->requireName();
        $shoppingListTransfer->requireCustomerReference();

        return $this->shoppingListRepository->findCustomerShoppingListByName($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkWritePermission(ShoppingListTransfer $shoppingListTransfer): bool
    {
        if (!$shoppingListTransfer->getIdShoppingList()) {
            return true;
        }

        if (!$shoppingListTransfer->getIdCompanyUser()) {
            return false;
        }

        return $this->can(
            'WriteShoppingListPermissionPlugin',
            $shoppingListTransfer->getIdCompanyUser(),
            $shoppingListTransfer->getIdShoppingList()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    protected function executeRemoveShoppingListTransaction(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $this->shoppingListItemOperation->deleteShoppingListItems($shoppingListTransfer);
        $this->shoppingListEntityManager->deleteShoppingListCompanyUsers($shoppingListTransfer);
        $this->shoppingListEntityManager->deleteCompanyBusinessUnitBlacklistByShoppingListId($shoppingListTransfer->getIdShoppingList());
        $this->shoppingListEntityManager->deleteShoppingListCompanyBusinessUnits($shoppingListTransfer);
        $this->shoppingListEntityManager->deleteShoppingListByName($shoppingListTransfer);
        $this->triggerShoppingListUnpublishEvent($shoppingListTransfer);

        return (new ShoppingListResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function executeSaveShoppingListTransaction(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->shoppingListEntityManager->saveShoppingList($shoppingListTransfer);

        if (!$shoppingListTransfer->getItems()->count()) {
            return $shoppingListTransfer;
        }

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $this->shoppingListItemOperation->saveShoppingListItemWithoutPermissionsCheck($shoppingListItemTransfer);
        }

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function triggerShoppingListUnpublishEvent(ShoppingListTransfer $shoppingListTransfer): void
    {
        $eventTransfer = (new EventEntityTransfer())
            ->setName(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH)
            ->setId($shoppingListTransfer->getIdShoppingList())
            ->setEvent(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH)
            ->setModifiedColumns([
                 $shoppingListTransfer->getCustomerReference() => ShoppingListTransfer::CUSTOMER_REFERENCE,
            ]);
        $this->eventFacade->trigger(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH, $eventTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function executeDeleteShoppingListItemsTransaction(ShoppingListTransfer $shoppingListTransfer): void
    {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $this->deleteShoppingListItem($shoppingListItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function deleteShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $shoppingListItemTransfer = $this->pluginExecutor->executeBeforeDeletePlugins($shoppingListItemTransfer);
        $this->shoppingListEntityManager->deleteShoppingListItem($shoppingListItemTransfer->getIdShoppingListItem());
    }
}
