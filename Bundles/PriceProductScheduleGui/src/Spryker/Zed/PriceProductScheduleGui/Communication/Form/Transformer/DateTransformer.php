<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;

class DateTransformer implements DataTransformerInterface
{
    /**
     * @param string|null $value
     *
     * @return \DateTime|null
     */
    public function transform($value)
    {
        if ($value === null) {
            return null;
        }

        return new DateTime($value);
    }

    /**
     * @param \DateTime|null $value
     *
     * @return string|null
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return null;
        }

        return $value->format('Y-m-d H:i:s');
    }
}
