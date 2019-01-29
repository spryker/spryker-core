<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Symfony\Component\Form\DataTransformerInterface;

class ArrayObjectToObjectTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        if ($value instanceof ArrayObject && $value->offsetExists(0)) {
            return $value->offsetGet(0);
        }

        return new MerchantAddressTransfer();
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return new ArrayObject();
        }

        $arrayObject = new ArrayObject();
        $arrayObject->append($value);

        return $arrayObject;
    }
}
