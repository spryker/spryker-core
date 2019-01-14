<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize\Model;

use Countable;

class ArrayFilter implements ArrayFilterInterface
{
    /**
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array)
    {
        $filteredArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->arrayFilterRecursive($value);
            }

            if ($this->isEmptyValue($value)) {
                continue;
            }

            $filteredArray[$key] = $value;
        }

        return $filteredArray;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isEmptyValue($value): bool
    {
        if (is_string($value)) {
            return $value === '';
        }

        if ($value instanceof Countable || is_array($value)) {
            return count($value) === 0;
        }

        return !(is_scalar($value) || $value);
    }
}
