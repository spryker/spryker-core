<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToNumberTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     */
    public function transform($value)
    {
        return (string)$value;
    }

    /**
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        return (int)$value;
    }
}
