<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Split\Validation;

class Messages
{

    const VALIDATE_QUANTITY_MESSAGE = 'Available quantity is equal or lower than provided.';
    const VALIDATE_BUNDLE_MESSAGE = 'Could not split when order item is in bundle.';
    const VALIDATE_DISCOUNTED_MESSAGE = 'Could not split when order item is discounted.';
    const VALIDATE_DISCOUNTED_OPTION_MESSAGE = 'Could not split when order item have discounted option.';
    const SPLIT_SUCCESS_MESSAGE = 'Order item with "%d" was successfully split.';

    /**
     * Static container class, no need instatiation
     */
    private function __construct()
    {
    }

}
