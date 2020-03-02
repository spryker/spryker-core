<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Business\Validator;

class OrderCustomReferenceValidator implements OrderCustomReferenceValidatorInterface
{
    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param string $orderCustomReference
     *
     * @return bool
     */
    public function isOrderCustomReferenceLengthValid(string $orderCustomReference): bool
    {
        if (!$orderCustomReference) {
            return true;
        }

        return mb_strlen($orderCustomReference) <= static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH;
    }
}
