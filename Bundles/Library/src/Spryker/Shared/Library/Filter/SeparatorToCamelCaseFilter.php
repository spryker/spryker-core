<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Filter;

class SeparatorToCamelCaseFilter implements FilterInterface
{

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var bool
     */
    protected $upperCaseFirst;

    /**
     * @param string $separator
     * @param bool $upperCaseFirst
     */
    public function __construct($separator, $upperCaseFirst = false)
    {
        $this->separator = $separator;
        $this->upperCaseFirst = $upperCaseFirst;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function filter($string)
    {
        // This should be the fastest solution compared to
        // any preg_*() or array_map() solution
        $explodedString = explode($this->separator, $string);

        $result = ($this->upperCaseFirst) ? '' : array_shift($explodedString);

        foreach ($explodedString as $part) {
            $result .= ucfirst($part);
        }

        return $result;
    }

}
