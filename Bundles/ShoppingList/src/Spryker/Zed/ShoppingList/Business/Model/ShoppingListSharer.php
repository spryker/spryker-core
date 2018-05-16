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
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListSharer implements ShoppingListSharerInterface
{
    use PermissionAwareTrait;

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
            ->requireIdCompanyBusinessUnit()
            ->requireIdShoppingList();

        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListShareRequestTransfer->getIdShoppingList());

        $shoppingListTransfer = $this->getShoppingListById($shoppingListTransfer);
        $shoppingListTransfer->setIdCompanyUser($shoppingListShareRequestTransfer->getRequesterId());

        if (!$shoppingListTransfer || !$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_UPDATE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $isShoppingListSharedCompanyBusinessUnit = $this->shoppingListRepository->isShoppingListSharedCompanyBusinessUnit(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyBusinessUnit()
        );

        if ($isShoppingListSharedCompanyBusinessUnit) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_RESHARE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $shoppingListCompanyBusinessUnitEntityTransfer = (new ShoppingListCompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($shoppingListShareRequestTransfer->getIdCompanyBusinessUnit())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyBusinessUnit($shoppingListCompanyBusinessUnitEntityTransfer);
        $shoppingListShareResponseTransfer = $shoppingListShareResponseTransfer->setIsSuccess(true);

        return $shoppingListShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListShareResponseTransfer
     */
    public function shareShoppingListWithCompanyUser(ShoppingListShareRequestTransfer $shoppingListShareRequestTransfer): ShoppingListShareResponseTransfer
    {
        $shoppingListShareRequestTransfer->requireIdShoppingListPermissionGroup()
            ->requireIdCompanyUser()
            ->requireIdShoppingList();

        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListShareRequestTransfer->getIdShoppingList());

        $shoppingListTransfer = $this->getShoppingListById($shoppingListTransfer);
        $shoppingListTransfer->setIdCompanyUser($shoppingListShareRequestTransfer->getRequesterId());

        if (!$shoppingListTransfer || !$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_UPDATE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $isShoppingListSharedCompanyUser = $this->shoppingListRepository->isShoppingListSharedCompanyUser(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyUser()
        );

        if ($isShoppingListSharedCompanyUser) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_RESHARE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $isShoppingListSharedCompanyUser = (new ShoppingListCompanyUserTransfer())
            ->setIdCompanyUser($shoppingListShareRequestTransfer->getIdCompanyUser())
            ->setIdShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setIdShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyUser($isShoppingListSharedCompanyUser);
        $shoppingListShareResponseTransfer = $shoppingListShareResponseTransfer->setIsSuccess(true);

        return $shoppingListShareResponseTransfer;
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
}
