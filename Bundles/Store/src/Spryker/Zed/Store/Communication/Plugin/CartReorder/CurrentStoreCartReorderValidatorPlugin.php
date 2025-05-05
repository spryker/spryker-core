<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\CartReorder;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 */
class CurrentStoreCartReorderValidatorPlugin extends AbstractPlugin implements CartReorderValidatorPluginInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER = 'store.cart_reorder.error.store_mismatch';

    /**
     * {@inheritDoc}
     * - Requires `CartReorderTransfer.order` to be set.
     * - Requires `CartReorderTransfer.order.store` to be set.
     * - Requires `CartReorderTransfer.quote` to be set.
     * - Requires `CartReorderTransfer.quote.store` to be set.
     * - Requires `CartReorderTransfer.quote.store.name` to be set.
     * - Gets current store.
     * - Validates that the store of the order and quote and current store are the same.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        $orderStoreName = $cartReorderTransfer->getOrderOrFail()->getStoreOrFail();
        $quoteStoreName = $cartReorderTransfer->getQuoteOrFail()->getStoreOrFail()->getNameOrFail();
        $currentStoreName = $this->getFacade()->getCurrentStore()->getNameOrFail();

        if ($quoteStoreName === $orderStoreName && $quoteStoreName === $currentStoreName) {
            return $cartReorderResponseTransfer;
        }

        return $cartReorderResponseTransfer->addError(
            (new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_STORE_MISMATCH_IN_CART_REORDER),
        );
    }
}
