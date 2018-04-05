<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListShareRequestTransfer;
use Generated\Shared\Transfer\ShoppingListShareResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\SpyShoppingListEntityTransfer;
use Generated\Shared\Transfer\SpyShoppingListItemEntityTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;
use Spryker\Zed\ShoppingList\ShoppingListConfig;

class Writer implements WriterInterface
{
    use TransactionTrait;

    protected const DUPLICATE_NAME_SHOPPING_LIST = 'A shopping list with the same name already exists.';

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
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\ShoppingListConfig $shoppingListConfig
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     */
    public function __construct(ShoppingListEntityManagerInterface $shoppingListEntityManager, ShoppingListRepositoryInterface $shoppingListRepository, ShoppingListConfig $shoppingListConfig, ShoppingListToProductFacadeInterface $productFacade)
    {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->productFacade = $productFacade;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListConfig = $shoppingListConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function validateAndSaveShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = new ShoppingListResponseTransfer();

        if ($this->checkShoppingListWithSameName($shoppingListTransfer)) {
            $shoppingListResponseTransfer
                ->setShoppingList($this->saveShoppingList($shoppingListTransfer))
                ->setIsSuccess(true);
        } else {
            $shoppingListResponseTransfer
                ->setIsSuccess(false)
                ->addError(static::DUPLICATE_NAME_SHOPPING_LIST);
        }

        return $shoppingListResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function removeShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListEntityTransfer = $this->createShoppingListEntityTransfer($shoppingListTransfer);
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListEntityTransfer) {
            $this->shoppingListEntityManager->deleteShoppingListItems($shoppingListEntityTransfer);
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
        $shoppingListItemTransfer->requireCustomerReference();
        $shoppingListItemTransfer->requireQuantity();

        if ($this->productFacade && !$this->productFacade->hasProductConcrete($shoppingListItemTransfer->getSku())) {
            return $shoppingListItemTransfer;
        }

        $existingShoppingListTransfer = $this->createShoppingListIfNotExists(
            $shoppingListItemTransfer->getCustomerReference(),
            $shoppingListItemTransfer->getShoppingListName()
        );

        $shoppingListItemTransfer->setFkShoppingList($existingShoppingListTransfer->getIdShoppingList());
        return $this->saveShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemById(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListItemResponseTransfer
    {
        $shoppingListItemTransfer->requireIdShoppingListItem();

        $this->shoppingListEntityManager->deleteShoppingListItem($this->createShoppingListItemEntityTransfer($shoppingListItemTransfer));

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function removeItemCollection(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($shoppingListItemCollectionTransfer) {
            foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
                $this->shoppingListEntityManager->deleteShoppingListItem(
                    $this->createShoppingListItemEntityTransfer($shoppingListItemTransfer)
                );
            }

            return (new ShoppingListItemResponseTransfer())->setIsSuccess(true);
        });
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function createShoppingListFromQuote(QuoteTransfer $quoteTransfer): ShoppingListTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
            $shoppingListTransfer = $this->createShoppingListIfNotExists($quoteTransfer->getCustomerReference());

            foreach ($quoteTransfer->getItems() as $item) {
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
            ->requireCustomerReference()
            ->requireShoppingListName();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference($shoppingListShareRequestTransfer->getCustomerReference())
            ->setName($shoppingListShareRequestTransfer->getShoppingListName());

        $shoppingListTransfer = $this->getShoppingListWithSameName($shoppingListTransfer);

        $shoppingListCompanyBusinessUnitEntityTransfer = $this->shoppingListRepository->getShoppingListCompanyBusinessUnit(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyBusinessUnit()
        );

        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();

        if ($shoppingListCompanyBusinessUnitEntityTransfer) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);

            return $shoppingListShareResponseTransfer;
        }

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
            ->requireCustomerReference()
            ->requireShoppingListName();

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setCustomerReference($shoppingListShareRequestTransfer->getCustomerReference())
            ->setName($shoppingListShareRequestTransfer->getShoppingListName());

        $shoppingListTransfer = $this->getShoppingListWithSameName($shoppingListTransfer);

        $shoppingListCompanyUserEntityTransfer = $this->shoppingListRepository->getShoppingListCompanyUser(
            $shoppingListTransfer->getIdShoppingList(),
            $shoppingListShareRequestTransfer->getIdCompanyUser()
        );

        $shoppingListShareResponseTransfer = new ShoppingListShareResponseTransfer();

        if ($shoppingListCompanyUserEntityTransfer) {
            $shoppingListShareResponseTransfer->setIsSuccess(false);

            return $shoppingListShareResponseTransfer;
        }

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
     * @param string $customerReference
     * @param string|null $shoppingListName
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createShoppingListIfNotExists(string $customerReference, string $shoppingListName = null): ShoppingListTransfer
    {
        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setName($shoppingListName)
            ->setCustomerReference($customerReference);

        if (!$shoppingListTransfer->getName()) {
            $shoppingListTransfer->setName($this->shoppingListConfig->getDefaultShoppingListName());
        }

        $existingShoppingListTransfer = $this->getShoppingListWithSameName($shoppingListTransfer);

        if (!$existingShoppingListTransfer) {
            $existingShoppingListTransfer = $this->saveShoppingList($shoppingListTransfer);
        }

        return $existingShoppingListTransfer;
    }
}
