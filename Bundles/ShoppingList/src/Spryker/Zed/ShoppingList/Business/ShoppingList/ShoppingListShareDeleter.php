<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingList;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\ShoppingListEvents;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListShareDeleter implements ShoppingListShareDeleterInterface
{
    use PermissionAwareTrait;
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
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToEventFacadeInterface $eventFacade
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToCompanyUserFacadeInterface $companyUserFacade,
        ShoppingListToEventFacadeInterface $eventFacade
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->companyUserFacade = $companyUserFacade;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function dismissShoppingListSharing(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer
    {
        $shoppingListDismissRequest->requireIdCompanyUser()
            ->requireIdShoppingList();

        if (!$this->can(
            'ReadShoppingListPermissionPlugin',
            $shoppingListDismissRequest->getIdCompanyUser(),
            $shoppingListDismissRequest->getIdShoppingList()
        )
        ) {
            return (new ShoppingListShareResponseTransfer())->setIsSuccess(false);
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListDismissRequest) {
            $this->executeDismissShoppingListSharingTransaction($shoppingListDismissRequest);
        });

        return (new ShoppingListShareResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    protected function executeDismissShoppingListSharingTransaction(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): ShoppingListShareResponseTransfer
    {
        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();
        $shoppingListShareResponseTransfer->setIsSuccess($this->deleteShoppingListCompanyUser($shoppingListDismissRequest));
        $shoppingListShareResponseTransfer->setIsSuccess(
            $this->createShoppingListCompanyBusinessUnitBlacklist($shoppingListDismissRequest) || $shoppingListShareResponseTransfer->getIsSuccess()
        );

        return $shoppingListShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return bool
     */
    protected function deleteShoppingListCompanyUser(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): bool
    {
        $shoppingListCompanyUserTransfer = (new ShoppingListCompanyUserTransfer())
            ->setIdShoppingList($shoppingListDismissRequest->getIdShoppingList())
            ->setIdCompanyUser($shoppingListDismissRequest->getIdCompanyUser());
        $shoppingListCompanyUserTransfer = $this->shoppingListRepository->findShoppingListCompanyUser($shoppingListCompanyUserTransfer);

        if (!$shoppingListCompanyUserTransfer) {
            return false;
        }

        $this->shoppingListEntityManager->deleteShoppingListCompanyUser($shoppingListCompanyUserTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return bool
     */
    protected function createShoppingListCompanyBusinessUnitBlacklist(ShoppingListDismissRequestTransfer $shoppingListDismissRequest): bool
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($shoppingListDismissRequest->getIdCompanyUser());
        $shoppingListCompanyBusinessUnitTransfer = $this->findShoppingListBusinessUnit($shoppingListDismissRequest->getIdShoppingList(), $companyUserTransfer);

        if ($shoppingListCompanyBusinessUnitTransfer === null) {
            return false;
        }

        $shoppingListCompanyBusinessUnitBlacklistTransfer = (new ShoppingListCompanyBusinessUnitBlacklistTransfer())
            ->setFkCompanyUser($shoppingListDismissRequest->getIdCompanyUser())
            ->setFkShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitTransfer->getIdShoppingListCompanyBusinessUnit());
        $loadedShoppingListCompanyBusinessUnitBlacklistTransfer = $this->shoppingListRepository
            ->findShoppingListCompanyBusinessUnitBlackList($shoppingListCompanyBusinessUnitBlacklistTransfer);
        if ($loadedShoppingListCompanyBusinessUnitBlacklistTransfer !== null) {
            return false;
        }

        $this->shoppingListEntityManager->createShoppingListCompanyBusinessUnitBlacklist($shoppingListCompanyBusinessUnitBlacklistTransfer);
        $this->triggerShoppingListUnpublishEvent($shoppingListDismissRequest->getIdShoppingList(), $companyUserTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollectionTransfer
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer|null
     */
    protected function findShoppingListCompanyBusinessUnitByIdBusinessUnit(
        ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollectionTransfer,
        int $idCompanyBusinessUnit
    ): ?ShoppingListCompanyBusinessUnitTransfer {
        foreach ($shoppingListCompanyBusinessUnitCollectionTransfer->getShoppingListCompanyBusinessUnits() as $shoppingListCompanyBusinessUnitTransfer) {
            if ($shoppingListCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $idCompanyBusinessUnit) {
                return $shoppingListCompanyBusinessUnitTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idShoppingList
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer|null
     */
    protected function findShoppingListBusinessUnit(int $idShoppingList, CompanyUserTransfer $companyUserTransfer): ?ShoppingListCompanyBusinessUnitTransfer
    {
        $shoppingListCompanyBusinessUnitCollectionTransfer = $this->shoppingListRepository->getShoppingListCompanyBusinessUnitsByShoppingListId(
            (new ShoppingListTransfer())->setIdShoppingList($idShoppingList)
        );
        $shoppingListCompanyBusinessUnitTransfer = $this->findShoppingListCompanyBusinessUnitByIdBusinessUnit(
            $shoppingListCompanyBusinessUnitCollectionTransfer,
            $companyUserTransfer->getFkCompanyBusinessUnit()
        );

        return $shoppingListCompanyBusinessUnitTransfer;
    }

    /**
     * @param int $idShoppingList
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    protected function triggerShoppingListUnpublishEvent(int $idShoppingList, CompanyUserTransfer $companyUserTransfer): void
    {
        if ($companyUserTransfer->getCustomer() === null) {
            return;
        }
        $eventTransfer = (new EventEntityTransfer())
            ->setName(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH)
            ->setId($idShoppingList)
            ->setEvent(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH)
            ->setModifiedColumns([
                 $companyUserTransfer->getCustomer()->getCustomerReference() => ShoppingListTransfer::CUSTOMER_REFERENCE,
            ]);
        $this->eventFacade->trigger(ShoppingListEvents::SHOPPING_LIST_UNPUBLISH, $eventTransfer);
    }
}
