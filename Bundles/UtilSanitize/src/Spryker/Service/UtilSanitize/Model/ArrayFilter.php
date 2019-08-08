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
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $result = $this->arrayFilterRecursive($value);
                if (!$result) {
                    continue;
                }

                $filteredArray[$key] = $result;
                continue;
            }
            if (is_string($value) && strlen($value)) {
                $filteredArray[$key] = $value;
                continue;
            }
            if ($value instanceof Countable && count($value) !== 0) {
                $filteredArray[$key] = $value;
                continue;
            }
            if (!$value instanceof Countable && $value) {
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function filterOutBlankValuesRecursively(array $array): array
    {
        $filteredArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->filterOutBlankValuesRecursively($value);
            }

            if ($this->isBlankValue($value)) {
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
    protected function isBlankValue($value): bool
    {
        if (is_string($value)) {
            return $value === '';
        }

        if (is_array($value) || $value instanceof Countable) {
            return count($value) === 0;
        }

        return !(is_scalar($value) || $value);
    }
}
