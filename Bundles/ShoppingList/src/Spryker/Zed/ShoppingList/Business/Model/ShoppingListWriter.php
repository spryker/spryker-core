<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;
use Spryker\Zed\ShoppingList\ShoppingListConfig;

class ShoppingListWriter implements ShoppingListWriterInterface
{
    use TransactionTrait;

    use PermissionAwareTrait;

    protected const DUPLICATE_NAME_SHOPPING_LIST = 'customer.account.shopping_list.error.duplicate_name';
    protected const CANNOT_UPDATE_SHOPPING_LIST = 'customer.account.shopping_list.error.cannot_update';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_SUCCESS = 'customer.account.shopping_list.create.success';
    protected const GLOSSARY_PARAM_NAME = '%name%';

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
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface $shoppingListEntityManager
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\ShoppingListConfig $shoppingListConfig
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ShoppingListEntityManagerInterface $shoppingListEntityManager,
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListConfig $shoppingListConfig,
        ShoppingListToMessengerFacadeInterface $messengerFacade
    ) {
        $this->shoppingListEntityManager = $shoppingListEntityManager;
        $this->productFacade = $productFacade;
        $this->shoppingListRepository = $shoppingListRepository;
        $this->shoppingListConfig = $shoppingListConfig;
        $this->messengerFacade = $messengerFacade;
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
        $this->addCreateSuccessMessage($shoppingListTransfer->getName());

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

        if (!$this->checkWritePermission($shoppingListTransfer)) {
            return (new ShoppingListResponseTransfer())->setIsSuccess(false);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($shoppingListTransfer) {
                $this->shoppingListEntityManager->deleteShoppingListItems($shoppingListTransfer);
                $this->shoppingListEntityManager->deleteShoppingListCompanyUsers($shoppingListTransfer);
                $this->shoppingListEntityManager->deleteShoppingListCompanyBusinessUnits($shoppingListTransfer);
                $this->shoppingListEntityManager->deleteShoppingListByName($shoppingListTransfer);

                return (new ShoppingListResponseTransfer())->setIsSuccess(true);
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
        return $this->shoppingListEntityManager->saveShoppingList($shoppingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkShoppingListWithSameName(ShoppingListTransfer $shoppingListTransfer): bool
    {
        return $shoppingListTransfer->getIdShoppingList() && $this->findCustomerShoppingListByName($shoppingListTransfer) === null;
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
     * @param string $shoppingListName
     *
     * @return void
     */
    protected function addCreateSuccessMessage(string $shoppingListName): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_SUCCESS)
                ->setParameters([static::GLOSSARY_PARAM_NAME => $shoppingListName])
        );
    }
}
