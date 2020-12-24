<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CartItemCheckoutDataValidator implements CartItemCheckoutDataValidatorInterface
{
    protected const GLOSSARY_PARAMETER_ID = '%id%';
    protected const GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED = 'checkout.validation.item.no_shipment_selected';

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateItemsShipment(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);

        if (!$checkoutDataTransfer->getShipments()->count()) {
            return $checkoutResponseTransfer;
        }

        $quoteTransfer = $checkoutDataTransfer->requireQuote()->getQuote();
        $itemGroupKeys = $this->extractItemGroupKeys($checkoutDataTransfer);

        $checkoutResponseTransfer = $this->validateItemLevel($checkoutResponseTransfer, $quoteTransfer, $itemGroupKeys);
        $checkoutResponseTransfer = $this->validateBundleItemLevel($checkoutResponseTransfer, $quoteTransfer, $itemGroupKeys);

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateItemLevel(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        QuoteTransfer $quoteTransfer,
        array $itemGroupKeys
    ): CheckoutResponseTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier() || in_array($itemTransfer->getGroupKey(), $itemGroupKeys, true)) {
                continue;
            }

            $checkoutResponseTransfer = $this->getErrorResponse(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED,
                [static::GLOSSARY_PARAMETER_ID => $itemTransfer->getGroupKey()]
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string[] $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function validateBundleItemLevel(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        QuoteTransfer $quoteTransfer,
        array $itemGroupKeys
    ): CheckoutResponseTransfer {
        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            if (in_array($itemTransfer->getGroupKey(), $itemGroupKeys, true)) {
                continue;
            }

            $checkoutResponseTransfer = $this->getErrorResponse(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED,
                [static::GLOSSARY_PARAMETER_ID => $itemTransfer->getGroupKey()]
            );
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return string[]
     */
    protected function extractItemGroupKeys(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $itemGroupKeys = [];

        foreach ($checkoutDataTransfer->getShipments() as $restShipmentsTransfer) {
            $itemGroupKeys[] = $restShipmentsTransfer->getItems();
        }

        return array_merge(...$itemGroupKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     * @param string[] $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function getErrorResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message,
        array $parameters = []
    ): CheckoutResponseTransfer {
        return $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage($message)
                    ->setParameters($parameters)
            );
    }
}
