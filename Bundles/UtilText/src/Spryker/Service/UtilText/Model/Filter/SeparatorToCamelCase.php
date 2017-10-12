<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model\Filter;

class SeparatorToCamelCase implements SeparatorToCamelCaseInterface
{
    /**
     * @param string $string
     * @param string $separator
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function filter($string, $separator = '-', $upperCaseFirst = false)
    {
        // This should be the fastest solution compared to
        // any preg_*() or array_map() solution
        $explodedString = explode($separator, $string);

        $result = ($upperCaseFirst) ? '' : array_shift($explodedString);

        foreach ($explodedString as $part) {
            $result .= ucfirst($part);
        }

        return $result;
    }
}
