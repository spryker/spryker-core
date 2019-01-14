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

            if ($this->isValidArray($value)) {
                $result = $this->arrayFilterRecursive($value);
                if (!$result) {
                    continue;
                }

                $filteredArray[$key] = $result;
                continue;
            }

            if ($this->isValidCountable($value) || $this->isValidScalar($value)) {
                $filteredArray[$key] = $value;
            }
        }

        return $filteredArray;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValidArray($value): bool
    {
        return is_array($value) && $value;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValidCountable($value): bool
    {
        return $value instanceof Countable && count($value) !== 0;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isValidScalar($value): bool
    {
        return is_bool($value) || is_numeric($value) || (is_string($value) && $value !== '') || (!$value instanceof Countable && $value);
    }
}
