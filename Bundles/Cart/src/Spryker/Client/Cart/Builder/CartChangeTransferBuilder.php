<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Builder;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Client\Cart\Validator\CartChangeItemValidatorInterface;

class CartChangeTransferBuilder implements CartChangeTransferBuilderInterface
{
    /**
     * @var \Spryker\Client\Cart\Validator\CartChangeItemValidatorInterface
     */
    protected $cartChangeItemValidator;

    /**
     * @param \Spryker\Client\Cart\Validator\CartChangeItemValidatorInterface $cartChangeItemValidator
     */
    public function __construct(
        CartChangeItemValidatorInterface $cartChangeItemValidator
    ) {
        $this->cartChangeItemValidator = $cartChangeItemValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function build(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $cartChangeItemTransfer) {
            if ($cartChangeItemTransfer->getProductConcrete() === null) {
                continue;
            }

            $cartChangeItemTransfer = $this->buildCartChangeItemTransfer($cartChangeItemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    protected function validateCartChangeItemTransfer(ItemTransfer $cartChangeItemTransfer): CartChangeItemValidationResponseTransfer
    {
        return $this->cartChangeItemValidator->validate($cartChangeItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildCartChangeItemTransfer(ItemTransfer $cartChangeItemTransfer): ItemTransfer
    {
        $cartChangeItemValidationResponseTransfer = $this->cartChangeItemValidator->validate($cartChangeItemTransfer);

        $cartChangeItemTransfer->fromArray(
            $cartChangeItemValidationResponseTransfer->modifiedToArray(),
            true
        );

        if (count($cartChangeItemValidationResponseTransfer->getCorrectValues())) {
            foreach ($cartChangeItemValidationResponseTransfer->getCorrectValues() as $correctValue) {
                $cartChangeItemTransfer->fromArray($correctValue, true);
            }
        }

        return $cartChangeItemTransfer;
    }
}
