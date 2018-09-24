<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingList;

use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitBlacklistTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListCompanyUserTransfer;
use Generated\Shared\Transfer\ShoppingListDismissRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListShareDeleter implements ShoppingListShareDeleterInterface
{
    use PermissionAwareTrait, TransactionTrait;

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
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->companyUserFacade = $companyUserFacade;
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
        $shoppingListShareResponseTransfer->setIsSuccess(
            $this->deleteShoppingListCompanyUser($shoppingListDismissRequest) || $this->createShoppingListCompanyBusinessUnitBlacklist($shoppingListDismissRequest)
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
        $shoppingListCompanyBusinessUnitTransfer = $this->findShoppingListBusinessUnit($shoppingListDismissRequest);

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

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollectionTransfer
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer|mixed|null
     */
    protected function findShoppingListCompanyBusinessUnitByIdBusinessUnit(
        ShoppingListCompanyBusinessUnitCollectionTransfer $shoppingListCompanyBusinessUnitCollectionTransfer,
        int $idCompanyBusinessUnit
    ) {
        foreach ($shoppingListCompanyBusinessUnitCollectionTransfer->getShoppingListCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($companyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $idCompanyBusinessUnit) {
                return $companyBusinessUnitTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListDismissRequestTransfer $shoppingListDismissRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListCompanyBusinessUnitTransfer|mixed|null
     */
    protected function findShoppingListBusinessUnit(ShoppingListDismissRequestTransfer $shoppingListDismissRequest)
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($shoppingListDismissRequest->getIdCompanyUser());
        $shoppingListCompanyBusinessUnitCollectionTransfer = $this->shoppingListRepository->getShoppingListCompanyBusinessUnitsByShoppingListId(
            (new ShoppingListTransfer())->setIdShoppingList($shoppingListDismissRequest->getIdShoppingList())
        );
        $shoppingListCompanyBusinessUnitTransfer = $this->findShoppingListCompanyBusinessUnitByIdBusinessUnit(
            $shoppingListCompanyBusinessUnitCollectionTransfer,
            $companyUserTransfer->getFkCompanyBusinessUnit()
        );

        return $shoppingListCompanyBusinessUnitTransfer;
    }
}
