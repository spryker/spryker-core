<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Validator;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class CartChangeItemValidator implements CartChangeItemValidatorInterface
{
    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemValidatorPluginInterface[]
     */
    protected $cartChangeItemValidatorPlugins;

    /**
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\CartChangeItemValidatorPluginInterface[] $cartChangeItemValidatorPlugins
     */
    public function __construct(array $cartChangeItemValidatorPlugins)
    {
        $this->cartChangeItemValidatorPlugins = $cartChangeItemValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): CartChangeItemValidationResponseTransfer
    {
        $cartChangeItemValidationResponseTransfer = new CartChangeItemValidationResponseTransfer();
        foreach ($this->cartChangeItemValidatorPlugins as $cartChangeItemValidatorPlugin) {
            $validationPluginResponse = $cartChangeItemValidatorPlugin->validateItemTransfer($itemTransfer);
            $cartChangeItemValidationResponseTransfer->fromArray($validationPluginResponse->modifiedToArray(), true);
        }

        return $cartChangeItemValidationResponseTransfer;
    }
}
