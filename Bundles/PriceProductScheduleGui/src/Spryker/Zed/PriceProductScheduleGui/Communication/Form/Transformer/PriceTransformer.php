<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class PriceTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return int|null
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        return $value / 100;
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        return (int)($value * 100);
    }
}
