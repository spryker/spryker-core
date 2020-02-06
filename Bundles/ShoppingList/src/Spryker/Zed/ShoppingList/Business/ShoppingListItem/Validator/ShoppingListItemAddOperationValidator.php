<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator;

use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;

//TODO change return of validate methods
class ShoppingListItemAddOperationValidator implements ShoppingListItemAddOperationValidatorInterface
{
    use PermissionAwareTrait;

    protected const ERROR_SHOPPING_LIST_ITEM_PRODUCT_NOT_FOUND = 'customer.account.shopping_list_item.error.product_not_found';

    protected const MAX_QUANTITY = 2147483647; // 32 bit integer
    protected const GLOSSARY_PARAM_SKU = '%sku%';

    protected const ERROR_SHOPPING_LIST_NOT_FOUND = 'customer.account.shopping_list.error.not_found';
    protected const ERROR_SHOPPING_LIST_ITEM_NOT_FOUND = 'customer.account.shopping_list_item.error.not_found';
    protected const ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED = 'customer.account.shopping_list.error.write_permission_required';
    protected const ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID = 'customer.account.shopping_list_item.error.quantity_not_valid';

    /**
     * @var \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface
     */
    protected $shoppingListResolver;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface
     */
    protected $messageAdder;

    /**
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Message\MessageAdderInterface $messageAdder
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface $shoppingListResolver
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListToProductFacadeInterface $productFacade,
        MessageAdderInterface $messageAdder,
        ShoppingListResolverInterface $shoppingListResolver,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListResolver = $shoppingListResolver;
        $this->pluginExecutor = $pluginExecutor;
        $this->productFacade = $productFacade;
        $this->messageAdder = $messageAdder;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function invalidateResponse(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        if ($shoppingListItemResponseTransfer->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemAddingSuccessMessage(
                $shoppingListItemResponseTransfer->getShoppingListItem()
                ?? new ShoppingListItemTransfer()
            );
        }

        return $shoppingListItemResponseTransfer;
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
            $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer);
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
    protected function validateShoppingListItemQuantity(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        $shoppingListItemTransfer->requireQuantity();

        $quantity = $shoppingListItemTransfer->getQuantity();
        if ($quantity <= 0 || $quantity > static::MAX_QUANTITY) {
            $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer);
            $shoppingListItemResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_ITEM_QUANTITY_NOT_VALID)
                ->setIsSuccess(false);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return bool
     */
    protected function validatePermissionForPerformingOperation(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): bool {
        if ($this->checkWritePermission($shoppingListTransfer)) {
            return true;
        }

        $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer);
        $shoppingListItemResponseTransfer
            ->addError(static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
            ->setIsSuccess(false);

        return false;
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

        $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer);
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
}
