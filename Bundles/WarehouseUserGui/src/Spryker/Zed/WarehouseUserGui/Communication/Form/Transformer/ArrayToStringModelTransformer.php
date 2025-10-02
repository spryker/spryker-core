<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<list<string>|null, string>
 */
class ArrayToStringModelTransformer implements DataTransformerInterface
{
    /**
     * @param list<string>|mixed $value
     *
     * @return string
     */
    public function transform($value): string
    {
        if (!count($value)) {
            return '';
        }

        return implode(',', $value);
    }

    /**
     * @param mixed|string $value
     *
     * @return list<string>
     */
    public function reverseTransform($value): array
    {
        if (!$value) {
            return [];
        }

        return explode(',', $value);
    }
}
