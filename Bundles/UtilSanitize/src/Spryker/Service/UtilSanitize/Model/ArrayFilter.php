<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize\Model;

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
                $filteredArray[$key] = $this->arrayFilterRecursive($value);
            } else {
                if (count($value) !== 0) {
                    $filteredArray[$key] = $value;
                }
            }
        }

        return $filteredArray;
    }
}
