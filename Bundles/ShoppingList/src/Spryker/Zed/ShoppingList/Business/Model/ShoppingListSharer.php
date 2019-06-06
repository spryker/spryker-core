<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListSharer implements ShoppingListSharerInterface
{
    use PermissionAwareTrait, TransactionTrait;

    protected const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';
    protected const CANNOT_RESHARE_SHOPPING_LIST = 'customer.account.shopping_list.share.share_shopping_list_fail';

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingListWithCompanyBusinessUnit(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        $shoppingListShareRequestTransfer->requireIdShoppingListPermissionGroup()
            ->requireIdCompanyBusinessUnit();

        $shoppingListTransfer = $this->resolveShoppingList($shoppingListShareRequestTransfer);
        if (!$shoppingListTransfer) {
            return $this->createErrorShareResponse(static::CANNOT_UPDATE_SHOPPING_LIST);
        }

        if ($this->shoppingListRepository->isShoppingListSharedWithCompanyBusinessUnit(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyBusinessUnit()
        )
        ) {
            return $this->createErrorShareResponse(static::CANNOT_RESHARE_SHOPPING_LIST);
        }

        $shoppingListCompanyBusinessUnitEntityTransfer = (new ShoppingListCompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($shoppingListShareRequestTransfer->getIdCompanyBusinessUnit())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitEntityTransfer);

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingListWithCompanyUser(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        $shoppingListShareRequestTransfer->requireIdShoppingListPermissionGroup()
            ->requireIdCompanyUser();

        $shoppingListTransfer = $this->resolveShoppingList($shoppingListShareRequestTransfer);
        if (!$shoppingListTransfer) {
            return $this->createErrorShareResponse(static::CANNOT_UPDATE_SHOPPING_LIST);
        }

        if ($this->shoppingListRepository->isShoppingListSharedWithCompanyUser(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyUser()
        )
        ) {
            return $this->createErrorShareResponse(static::CANNOT_RESHARE_SHOPPING_LIST);
        }

        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($shoppingListShareRequestTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyUser($shoppingListCompanyUserTransfer);

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function updateShoppingListSharedEntities(ShoppingListTransfer $shoppingListTransfer): ShoppingListShareResponseTransfer
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListTransfer) {
            $this->executeUpdateShoppingListSharedEntitiesTransaction($shoppingListTransfer);
        });

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function executeUpdateShoppingListSharedEntitiesTransaction(ShoppingListTransfer $shoppingListTransfer): void
    {
        $this->updateShoppingListCompanyUsers($shoppingListTransfer);
        $this->updateShoppingListCompanyBusinessUnits($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function updateShoppingListCompanyUsers(ShoppingListTransfer $shoppingListTransfer): void
    {
        $sharedShoppingListCompanyUserIds = [];
        $shoppingListCompanyUserCollectionTransfer = $this->shoppingListRepository
            ->getShoppingListCompanyUsersByShoppingListId($shoppingListTransfer);

        foreach ($shoppingListCompanyUserCollectionTransfer->getShoppingListCompanyUsers() as $sharedShoppingListCompanyUserTransfer) {
            $sharedShoppingListCompanyUserIds[$sharedShoppingListCompanyUserTransfer->getIdShoppingListCompanyUser()] =
                $sharedShoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup();
        }

        foreach ($shoppingListTransfer->getSharedCompanyUsers() as $shoppingListCompanyUserTransfer) {
            $this->updateShareShoppingListCompanyUser($shoppingListCompanyUserTransfer, $sharedShoppingListCompanyUserIds);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return void
     */
    protected function updateShoppingListCompanyBusinessUnits(ShoppingListTransfer $shoppingListTransfer): void
    {
        $sharedShoppingListCompanyBusinessUnitIds = [];
        $shoppingListCompanyBusinessUnitCollectionTransfer = $this->shoppingListRepository
            ->getShoppingListCompanyBusinessUnitsByShoppingListId($shoppingListTransfer);

        foreach ($shoppingListCompanyBusinessUnitCollectionTransfer->getShoppingListCompanyBusinessUnits() as $sharedShoppingListCompanyBusinessUnitTransfer) {
            $sharedShoppingListCompanyBusinessUnitIds[$sharedShoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit()] =
                $sharedShoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup();
        }

        foreach ($shoppingListTransfer->getSharedCompanyBusinessUnits() as $shoppingListCompanyBusinessUnitTransfer) {
            $this->updateShareShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer, $sharedShoppingListCompanyBusinessUnitIds);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     * @param array $sharedShoppingListCompanyUserIds
     *
     * @return void
     */
    protected function updateShareShoppingListCompanyUser(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer,
        array $sharedShoppingListCompanyUserIds
    ): void {
        if (!$this->checkExistingBeforeUpdateCompanyUser($shoppingListCompanyUserTransfer, $sharedShoppingListCompanyUserIds)) {
            return;
        }

        if (!$shoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup()) {
            $this->shoppingListEntityManager->deleteShoppingListCompanyUser($shoppingListCompanyUserTransfer);

            return;
        }

        $this->shoppingListEntityManager->saveShoppingListCompanyUser($shoppingListCompanyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer
     * @param array $sharedShoppingListCompanyUserIds
     *
     * @return bool
     */
    protected function checkExistingBeforeUpdateCompanyUser(
        ShoppingListCompanyUserTransfer $shoppingListCompanyUserTransfer,
        array $sharedShoppingListCompanyUserIds
    ): bool {
        $isExists = array_key_exists($shoppingListCompanyUserTransfer->getIdShoppingListCompanyUser(), $sharedShoppingListCompanyUserIds);

        if (!$isExists && !$shoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup()) {
            return false;
        }

        if ($isExists && $sharedShoppingListCompanyUserIds[$shoppingListCompanyUserTransfer->getIdShoppingListCompanyUser()] ===
            $shoppingListCompanyUserTransfer->getIdShoppingListPermissionGroup()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     * @param array $sharedShoppingListCompanyBusinessUnitIds
     *
     * @return void
     */
    protected function updateShareShoppingListCompanyBusinessUnit(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer,
        array $sharedShoppingListCompanyBusinessUnitIds
    ): void {
        if (!$this->checkExistingBeforeUpdateCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer, $sharedShoppingListCompanyBusinessUnitIds)) {
            return;
        }

        if (!$shoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup()) {
            $this->shoppingListEntityManager->deleteCompanyBusinessUnitBlacklistByBusinessUnitId($shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit());
            $this->shoppingListEntityManager->deleteShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer);

            return;
        }

        $this->shoppingListEntityManager->saveShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer
     * @param array $sharedShoppingListCompanyBusinessUnitIds
     *
     * @return bool
     */
    protected function checkExistingBeforeUpdateCompanyBusinessUnit(
        ShoppingListCompanyBusinessUnitTransfer $shoppingListCompanyBusinessUnitTransfer,
        array $sharedShoppingListCompanyBusinessUnitIds
    ): bool {
        $isExists = array_key_exists(
            $shoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit(),
            $sharedShoppingListCompanyBusinessUnitIds
        );

        if (!$isExists && !$shoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup()) {
            return false;
        }

        if ($isExists && $sharedShoppingListCompanyBusinessUnitIds[$shoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit()] ===
            $shoppingListCompanyBusinessUnitTransfer->getIdShoppingListPermissionGroup()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function unShareShoppingListCompanyBusinessUnit(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        $idCompanyBusinessUnit = $shoppingListShareRequestTransfer->getIdCompanyBusinessUnit();

        $isCompanyBusinessUnitSharedWithShoppingLists = $this->shoppingListRepository
            ->isCompanyBusinessUnitSharedWithShoppingLists($idCompanyBusinessUnit);

        if (!$isCompanyBusinessUnitSharedWithShoppingLists) {
            return (new ShoppingListShareResponseTransfer())->setIsSuccess(false);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($idCompanyBusinessUnit) {
                return $this->executeRemoveShoppingListCompanyBusinessUnitTransaction($idCompanyBusinessUnit);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function unShareCompanyUserShoppingLists(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        $idCompanyUser = $shoppingListShareRequestTransfer->getIdCompanyUser();

        $this->shoppingListEntityManager->deleteShoppingListsCompanyUserByCompanyUserId($idCompanyUser);

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    protected function resolveShoppingList(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ?ShoppingListTransfer
    {
        $shoppingListShareRequestTransfer->requireShoppingListOwnerId()
            ->requireIdShoppingList();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListShareRequestTransfer->getIdShoppingList());

        $shoppingListTransfer = $this->getShoppingListById($shoppingListTransfer);
        $shoppingListTransfer->setIdCompanyUser($shoppingListShareRequestTransfer->getShoppingListOwnerId());

        if ($shoppingListTransfer && $this->checkWritePermission($shoppingListTransfer)) {
            return $shoppingListTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    protected function getShoppingListById(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListTransfer->requireIdShoppingList();

        return $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    protected function createErrorShareResponse(string $errorMessage): ShoppingListShareResponseTransfer
    {
        return (new ShoppingListShareResponseTransfer())
            ->setIsSuccess(false)
            ->setError($errorMessage);
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
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    protected function executeRemoveShoppingListCompanyBusinessUnitTransaction(int $idCompanyBusinessUnit): ShoppingListShareResponseTransfer
    {
        $this->shoppingListEntityManager->deleteShoppingListCompanyBusinessUnitsByCompanyBusinessUnitId($idCompanyBusinessUnit);

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }
}
