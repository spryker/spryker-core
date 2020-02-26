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

class ShoppingListItemOperationValidator implements ShoppingListItemOperationValidatorInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemAddOperationValidatorInterface
     */
    protected $shoppingListItemAddOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemUpdateOperationValidatorInterface
     */
    protected $shoppingListItemUpdateOperationValidator;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemDeleteOperationValidatorInterface
     */
    protected $shoppingListItemDeleteOperationValidator;

    /**
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemAddOperationValidatorInterface $shoppingListItemAddOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemUpdateOperationValidatorInterface $shoppingListItemUpdateOperationValidator
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\Validator\ShoppingListItemDeleteOperationValidatorInterface $shoppingListItemDeleteOperationValidator
     */
    public function __construct(
        ShoppingListItemAddOperationValidatorInterface $shoppingListItemAddOperationValidator,
        ShoppingListItemUpdateOperationValidatorInterface $shoppingListItemUpdateOperationValidator,
        ShoppingListItemDeleteOperationValidatorInterface $shoppingListItemDeleteOperationValidator
    ) {
        $this->shoppingListItemAddOperationValidator = $shoppingListItemAddOperationValidator;
        $this->shoppingListItemUpdateOperationValidator = $shoppingListItemUpdateOperationValidator;
        $this->shoppingListItemDeleteOperationValidator = $shoppingListItemDeleteOperationValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function invalidateItemAddResponse(
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->shoppingListItemAddOperationValidator->invalidateResponse($shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemAddRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->shoppingListItemAddOperationValidator
            ->validateRequest($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     * @param \Generated\Shared\Transfer\ShoppingListResponseTransfer $shoppingListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function validateItemAddBulkRequest(
        ShoppingListTransfer $shoppingListTransfer,
        ShoppingListResponseTransfer $shoppingListResponseTransfer
    ): ShoppingListResponseTransfer {
        return $this->shoppingListItemAddOperationValidator
            ->validateBulkRequest($shoppingListTransfer, $shoppingListResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemDeleteRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->shoppingListItemDeleteOperationValidator
            ->validateRequest($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function validateItemUpdateRequest(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        ShoppingListItemResponseTransfer $shoppingListItemResponseTransfer
    ): ShoppingListItemResponseTransfer {
        return $this->shoppingListItemUpdateOperationValidator
            ->validateRequest($shoppingListItemTransfer, $shoppingListItemResponseTransfer);
    }
}
