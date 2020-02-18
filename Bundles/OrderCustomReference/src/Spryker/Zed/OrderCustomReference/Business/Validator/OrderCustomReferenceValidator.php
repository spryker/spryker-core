<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Validator;

class OrderCustomReferenceValidator implements OrderCustomReferenceValidatorInterface
{
    protected const ORDER_CUSTOM_REFERENCE_MIN_LENGTH = 1;
    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param string|null $orderCustomReference
     *
     * @return bool
     */
    public function isOrderCustomReferenceLengthValid(?string $orderCustomReference): bool
    {
        if (!$orderCustomReference) {
            return false;
        }

        $orderCustomReferenceLength = mb_strlen($orderCustomReference);

        return $orderCustomReferenceLength >= static::ORDER_CUSTOM_REFERENCE_MIN_LENGTH
            && $orderCustomReferenceLength <= static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH;
    }
}
