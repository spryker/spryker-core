<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\ShoppingListFromCartRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListCompanyUserEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;
use Spryker\Zed\ShoppingList\ShoppingListConfig;

class Writer implements WriterInterface
{
    use TransactionTrait;

    use PermissionAwareTrait;

    protected const DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';

    protected const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';

    protected const CANNOT_RESHARE_SHOPPING_LIST = 'customer.account.shopping_list.share.share_shopping_list_fail';

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface
     */
    protected $shoppingListEntityManager;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\ShoppingListConfig
     */
    protected $shoppingListConfig;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\ShoppingListConfig $shoppingListConfig
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct(ShoppingListEntityManagerInterface $shoppingListEntityManager, ShoppingListToProductFacadeInterface $productFacade, ShoppingListRepositoryInterface $shoppingListRepository, ShoppingListConfig $shoppingListConfig, ShoppingListToPersistentCartFacadeInterface $persistentCartFacade)
    {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->productFacade = $productFacade;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListConfig = $shoppingListConfig;
        $this->persistentCartFacade = $persistentCartFacade;
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

        if (!$this->checkShoppingListWithSameName($shoppingListTransfer)) {
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
        $shoppingList = $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);

        if (!$this->checkWritePermission($shoppingList)) {
            return (new ShoppingListResponseTransfer())->setIsSuccess(false);
        }

        $shoppingListEntityTransfer = $this->createShoppingListEntityTransfer($shoppingListTransfer);
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListEntityTransfer) {
            $this->shoppingListEntityManager->deleteShoppingListItems($shoppingListEntityTransfer);
            $this->shoppingListEntityManager->deleteShoppingListCompanyUsers($shoppingListEntityTransfer);
            $this->shoppingListEntityManager->deleteShoppingListCompanyBusinessUnits($shoppingListEntityTransfer);
            $this->shoppingListEntityManager->deleteShoppingListByName($shoppingListEntityTransfer);

            return (new ShoppingListResponseTransfer())->setIsSuccess(true);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListItemTransfer->requireSku();
        $shoppingListItemTransfer->requireQuantity();

        if ($this->productFacade && !$this->productFacade->hasProductConcrete($shoppingListItemTransfer->getSku())) {
            return $shoppingListItemTransfer;
        }

        $shoppingListTransfer = (new ShoppingListTransfer())->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList());

        if (!$shoppingListItemTransfer->getFkShoppingList()) {
            $shoppingListTransfer = $this->createDefaultShoppingListIfNotExists(
                $shoppingListItemTransfer->getCustomerReference()
            );
        }

        $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());
        return $this->saveShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer->requireIdShoppingListItem()->requireFkShoppingList();

        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById(
            (new ShoppingListTransfer())->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
        );
        $shoppingListTransfer->setRequesterId($shoppingListItemTransfer->getRequesterId());

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            return (new ShoppingListItemResponseTransfer())->setIsSuccess(false);
        }

        $this->shoppingListEntityManager->deleteShoppingListItem($this->createShoppingListItemEntityTransfer($shoppingListItemTransfer));

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function saveShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemTransfer
    {
        $shoppingListEntityTransfer = $this->shoppingListEntityManager->saveShoppingListItem(
            $this->createShoppingListItemEntityTransfer($shoppingListItemTransfer)
        );

        return $shoppingListItemTransfer->fromArray($shoppingListEntityTransfer->toArray(), true);
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
            $quoteTransfer = $this->persistentCartFacade->findQuote(
                $shoppingListFromCartRequestTransfer->getIdQuote(),
                $shoppingListFromCartRequestTransfer->getCustomer()
            );

            $shoppingListTransfer = $this->createShoppingListIfNotExists(
                $shoppingListFromCartRequestTransfer->getCustomer()->getCustomerReference(),
                $shoppingListFromCartRequestTransfer->getShoppingListName()
            );

            foreach ($quoteTransfer->getQuoteTransfer()->getItems() as $item) {
                $shoppingListItemTransfer = (new ShoppingListItemTransfer())
                    ->setFkShoppingList($shoppingListTransfer->getIdShoppingList())
                    ->setQuantity($item->getQuantity())
                    ->setSku($item->getSku());

                $this->saveShoppingListItem($shoppingListItemTransfer);
            }

            return $shoppingListTransfer;
        });
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
        $shoppingListTransfer->setRequesterId($shoppingListShareRequestTransfer->getRequesterId());

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_UPDATE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $shoppingListCompanyBusinessUnitEntityTransfer = $this->shoppingListRepository->getShoppingListCompanyBusinessUnit(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyBusinessUnit()
        );

        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();

        if ($shoppingListCompanyBusinessUnitEntityTransfer) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_RESHARE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $shoppingListCompanyBusinessUnitEntityTransfer = (new SpyShoppingListCompanyBusinessUnitEntityTransfer())
            ->setFkCompanyBusinessUnit($shoppingListShareRequestTransfer->getIdCompanyBusinessUnit())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyBusinessUnitEntity($shoppingListCompanyBusinessUnitEntityTransfer);
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
        $shoppingListTransfer->setRequesterId($shoppingListShareRequestTransfer->getRequesterId());

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_UPDATE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $shoppingListCompanyUserEntityTransfer = $this->shoppingListRepository->getShoppingListCompanyUser(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyUser()
        );

        if ($shoppingListCompanyUserEntityTransfer) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);
            $shoppingListShareResponseTransfer->setError(static::CANNOT_RESHARE_SHOPPING_LIST);

            return $shoppingListShareResponseTransfer;
        }

        $shoppingListCompanyUserEntityTransfer = (new SpyShoppingListCompanyUserEntityTransfer())
            ->setFkCompanyUser($shoppingListShareRequestTransfer->getIdCompanyUser())
            ->setFkShoppingList($shoppingListTransfer->getIdShoppingList())
            ->setFkShoppingListPermissionGroup($shoppingListShareRequestTransfer->getIdShoppingListPermissionGroup());

        $this->shoppingListEntityManager->saveShoppingListCompanyUserEntity($shoppingListCompanyUserEntityTransfer);
        $shoppingListShareResponseTransfer = $shoppingListShareResponseTransfer->setIsSuccess(true);

        return $shoppingListShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function saveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListEntityTransfer = $this->shoppingListEntityManager->saveShoppingList(
            $this->createShoppingListEntityTransfer($shoppingListTransfer)
        );

        return $shoppingListTransfer->fromArray($shoppingListEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkShoppingListWithSameName(ShoppingListTransfer $shoppingListTransfer): bool
    {
        return $this->getShoppingListWithSameName($shoppingListTransfer) === null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListEntityTransfer
     */
    protected function createShoppingListEntityTransfer(ShoppingListTransfer $shoppingListTransfer): SpyShoppingListEntityTransfer
    {
        return (new SpyShoppingListEntityTransfer())->fromArray($shoppingListTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer
     */
    protected function createShoppingListItemEntityTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): SpyShoppingListItemEntityTransfer
    {
        return (new SpyShoppingListItemEntityTransfer())->fromArray($shoppingListItemTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer|null
     */
    protected function getShoppingListWithSameName(ShoppingListTransfer $shoppingListTransfer): ?ShoppingListTransfer
    {
        $shoppingListTransfer->requireName();
        $shoppingListTransfer->requireCustomerReference();

        return $this->shoppingListRepository->findCustomerShoppingListWithSameName($shoppingListTransfer);
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
     * @param string $customerReference
     * @param string|null $shoppingListName
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createShoppingListIfNotExists(string $customerReference, ?string $shoppingListName = null): ShoppingListTransfer
    {
        if (!$shoppingListName) {
            return $this->createDefaultShoppingListIfNotExists($customerReference);
        }

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setName($shoppingListName)
            ->setCustomerReference($customerReference);

        $existingShoppingListTransfer = $this->getShoppingListWithSameName($shoppingListTransfer);

        if (!$existingShoppingListTransfer) {
            $existingShoppingListTransfer = $this->saveShoppingList($shoppingListTransfer);
        }

        return $existingShoppingListTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createDefaultShoppingListIfNotExists(string $customerReference): ShoppingListTransfer
    {
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setName($this->shoppingListConfig->getDefaultShoppingListName())
            ->setCustomerReference($customerReference);

        $existingShoppingListTransfer = $this->getShoppingListWithSameName($shoppingListTransfer);

        if (!$existingShoppingListTransfer) {
            $existingShoppingListTransfer = $this->saveShoppingList($shoppingListTransfer);
        }

        return $existingShoppingListTransfer;
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

        if (!$shoppingListTransfer->getRequesterId()) {
            return false;
        }

        return $this->can(
            'WriteShoppingListPermissionPlugin',
            $shoppingListTransfer->getRequesterId(),
            $shoppingListTransfer->getIdShoppingList()
        );
    }
}
