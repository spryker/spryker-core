<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderItemSplit\Validation;

class Messages
{
    const VALIDATE_QUANTITY_MESSAGE = 'Available quantity is equal or lower than provided.';
    const VALIDATE_BUNDLE_MESSAGE = 'Could not split when order item is in bundle.';
    const VALIDATE_DISCOUNTED_MESSAGE = 'Could not split when order item is discounted.';
    const VALIDATE_DISCOUNTED_OPTION_MESSAGE = 'Could not split when order item have discounted option.';
    const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    private function __construct()
    {
    }

}
