<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListItemAddOperationValidator extends ShoppingListItemOperationValidator implements ShoppingListItemBulkOperationValidatorInterface
{
    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND = 'customer.account.shopping_list_item.error.product_not_found';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS = 'customer.account.shopping_list.item.add.success';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED = 'customer.account.shopping_list.item.add.failed';

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface
     */
    protected $shoppingListResolver;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface $shoppingListResolver
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToMessengerFacadeInterface $messengerFacade,
        ShoppingListResolverInterface $shoppingListResolver,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        parent::__construct($productFacade, $shoppingListRepository, $messengerFacade);

        $this->shoppingListResolver = $shoppingListResolver;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        return $this->validateShoppingListItemToBeAdded($shoppingListItemTransfer, $shoppingListItemResponseTransfer)
            && $this->resolveShoppingListItemParent($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return bool
     */
    public function validateBulkRequest(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): bool {
        $shoppingListTransfer
            ->requireIdCompanyUser()
            ->requireCustomerReference();

        $shoppingListTransfer = $this->sanitizeItems(
            $this->resolveShoppingList($shoppingListTransfer)
        );

        if (!$this->isApplicableForAddItems($shoppingListTransfer, $shoppingListResponseTransfer)) {
            return false;
        }

        $shoppingListResponseTransfer->setShoppingList($shoppingListTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function validateShoppingListItemToBeAdded(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        return $this->validateShoppingListItemQuantity($shoppingListItemTransfer, $shoppingListItemResponseTransfer)
            && $this->validateShoppingListItemSku($shoppingListItemTransfer, $shoppingListItemResponseTransfer)
            && $this->performShoppingListItemAddPreCheckPlugins($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function validateShoppingListItemSku(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireSku();

        if (!$this->productFacade->hasProductConcrete($shoppingListItemTransfer->getSku())) {
            $this->addFailedMessage($shoppingListItemTransfer);
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND)
                ->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function resolveShoppingListItemParent(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListTransfer = $this->resolveShoppingList(
            $this->createShoppingListTransfer($shoppingListItemTransfer)
        );
        $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        return $this->validatePermissionForPerformingOperation(
            $shoppingListItemTransfer,
            $shoppingListTransfer,
            $shoppingListItemResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function performShoppingListItemAddPreCheckPlugins(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListPreAddItemCheckResponseTransfer = $this->pluginExecutor->executeAddShoppingListItemPreCheckPlugins($shoppingListItemTransfer);

        if ($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess()) {
            return true;
        }

        foreach ($shoppingListPreAddItemCheckResponseTransfer->getMessages() as $messageTransfer) {
            $shoppingListItemResponseTransfer->addError($messageTransfer->getValue());
        }

        $this->addFailedMessage($shoppingListItemTransfer);
        $shoppingListItemResponseTransfer->setIsSuccess(false);

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return bool
     */
    protected function isApplicableForAddItems(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): bool {
        if (!$this->checkWritePermission($shoppingListTransfer)) {
            $shoppingListResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
                ->setIsSuccess(false);

            return false;
        }

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();
            if (!$this->validateShoppingListItemToBeAdded($shoppingListItemTransfer, $shoppingListItemResponseTransfer)) {
                $shoppingListResponseTransfer
                    ->setErrors($shoppingListItemResponseTransfer->getErrors())
                    ->setIsSuccess(false);

                return false;
            }
        }

        return true;
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
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function createShoppingListTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListTransfer {
        return (new ShoppingListTransfer())
            ->setIdShoppingList($shoppingListItemTransfer->getFkShoppingList())
            ->setIdCompanyUser($shoppingListItemTransfer->getIdCompanyUser())
            ->setCustomerReference($shoppingListItemTransfer->getCustomerReference());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function addFailedMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->messengerFacade->addErrorMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_FAILED)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $shoppingListItemTransfer->getSku()])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return void
     */
    protected function addSuccessMessage(ShoppingListItemTransfer $shoppingListItemTransfer): void
    {
        $this->messengerFacade->addSuccessMessage(
            (new MessageTransfer())
                ->setValue(static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_ITEM_ADD_SUCCESS)
                ->setParameters([static::GLOSSARY_PARAM_SKU => $shoppingListItemTransfer->getSku()])
        );
    }
}
