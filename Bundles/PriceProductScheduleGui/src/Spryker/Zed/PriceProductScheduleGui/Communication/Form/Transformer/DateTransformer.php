<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Transformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<string|null, \DateTime|null>
 */
class DateTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected const PATTERN_DATE_FORMAT = 'Y-m-d H:i:s';

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

        return $value->format(static::PATTERN_DATE_FORMAT);
    }
}
