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
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;

class ShoppingListItemAddOperationValidator implements ShoppingListItemAddOperationValidatorInterface
{
    use PermissionAwareTrait;

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
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface
     */
    protected $shoppingListItemValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface
     */
    protected $messageAdder;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface
     */
    protected $permissionValidator;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemValidatorInterface $shoppingListItemValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Messenger\ShoppingListItemMessageAdderInterface $messageAdder
     * @param \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface $shoppingListResolver
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemPermissionValidatorInterface $permissionValidator
     */
    public function __construct(
        ShoppingListItemValidatorInterface $shoppingListItemValidator,
        ShoppingListItemMessageAdderInterface $messageAdder,
        ShoppingListResolverInterface $shoppingListResolver,
        ShoppingListItemPluginExecutorInterface $pluginExecutor,
        ShoppingListItemPermissionValidatorInterface $permissionValidator
    ) {
        $this->shoppingListResolver = $shoppingListResolver;
        $this->pluginExecutor = $pluginExecutor;
        $this->shoppingListItemValidator = $shoppingListItemValidator;
        $this->messageAdder = $messageAdder;
        $this->permissionValidator = $permissionValidator;
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
                $shoppingListItemResponseTransfer->getShoppingListItem()->getSku()
            );
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $validatedShoppingListItemResponseTransfer = $this->validateShoppingListItemToBeAdded(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$validatedShoppingListItemResponseTransfer->getIsSuccess()) {
            return $validatedShoppingListItemResponseTransfer;
        }

        $shoppingListItemResponseTransferWithResolvedItemParent = $this->resolveShoppingListItemParent(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$shoppingListItemResponseTransferWithResolvedItemParent->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer->getSku());

            return $shoppingListItemResponseTransferWithResolvedItemParent;
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function validateBulkRequest(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer {
        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());
        }

        if (!$this->permissionValidator->checkWritePermission($shoppingListTransfer)) {
            $shoppingListResponseTransfer
                ->addError(static::ERROR_SHOPPING_LIST_WRITE_PERMISSION_REQUIRED)
                ->setIsSuccess(false);

            return $shoppingListResponseTransfer;
        }

        foreach ($shoppingListTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemResponseTransfer = new ShoppingListItemResponseTransfer();
            if (!$this->validateShoppingListItemToBeAdded($shoppingListItemTransfer, $shoppingListItemResponseTransfer)->getIsSuccess()) {
                $shoppingListResponseTransfer
                    ->setErrors($shoppingListItemResponseTransfer->getErrors())
                    ->setIsSuccess(false);

                return $shoppingListResponseTransfer;
            }
        }

        $shoppingListResponseTransfer->setShoppingList($shoppingListTransfer);

        return $shoppingListResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function validateShoppingListItemToBeAdded(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransferWithValidatedQuantity = $this->shoppingListItemValidator
            ->validateShoppingListItemQuantity($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
        if (!$shoppingListItemResponseTransferWithValidatedQuantity->getIsSuccess()) {
            return $shoppingListItemResponseTransferWithValidatedQuantity;
        }

        $shoppingListItemResponseTransferWithValidatedSku = $this->shoppingListItemValidator
            ->validateShoppingListItemSku($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
        if (!$shoppingListItemResponseTransferWithValidatedSku->getIsSuccess()) {
            return $shoppingListItemResponseTransferWithValidatedSku;
        }

        $shoppingListItemResponseTransferWithPerformedItemAddPreCheckPlugins = $this->performShoppingListItemAddPreCheckPlugins(
            $shoppingListItemTransfer,
            $shoppingListItemResponseTransfer
        );
        if (!$shoppingListItemResponseTransferWithPerformedItemAddPreCheckPlugins->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer->getSku());

            return $shoppingListItemResponseTransferWithPerformedItemAddPreCheckPlugins;
        }

        return $shoppingListItemResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function resolveShoppingListItemParent(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListTransfer = $this->createShoppingListTransfer($shoppingListItemTransfer);
        if (!$shoppingListTransfer->getIdShoppingList()) {
            $shoppingListTransfer = $this->shoppingListResolver
                ->createDefaultShoppingListIfNotExists($shoppingListTransfer->getCustomerReference())
                ->setIdCompanyUser($shoppingListTransfer->getIdCompanyUser())
                ->setItems($shoppingListTransfer->getItems());
        }

        $shoppingListItemTransfer->setFkShoppingList($shoppingListTransfer->getIdShoppingList());

        $shoppingListItemResponseTransferWithValidatedPermission = $this->permissionValidator
            ->validatePermissionForPerformingOperation($shoppingListTransfer, $shoppingListItemResponseTransfer);
        if (!$shoppingListItemResponseTransferWithValidatedPermission->getIsSuccess()) {
            $this->messageAdder->addShoppingListItemAddingFailedMessage($shoppingListItemTransfer->getSku());

            return $shoppingListItemResponseTransferWithValidatedPermission;
        }

        return $shoppingListItemResponseTransfer->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    protected function performShoppingListItemAddPreCheckPlugins(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListPreAddItemCheckResponseTransfer = $this->pluginExecutor->executeAddShoppingListItemPreCheckPlugins($shoppingListItemTransfer);

        if ($shoppingListPreAddItemCheckResponseTransfer->getIsSuccess()) {
            return $shoppingListItemResponseTransfer;
        }

        foreach ($shoppingListPreAddItemCheckResponseTransfer->getMessages() as $messageTransfer) {
            $shoppingListItemResponseTransfer->addError($messageTransfer->getValue());
        }

        $shoppingListItemResponseTransfer->setIsSuccess(false);

        return $shoppingListItemResponseTransfer;
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
