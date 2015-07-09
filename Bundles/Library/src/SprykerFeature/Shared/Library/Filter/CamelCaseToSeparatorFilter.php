<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Filter;

class CamelCaseToSeparatorFilter implements FilterInterface
{

    /**
     * @var string
     */
    protected $separator;

    /**
     * @param string $separator
     */
    public function __construct($separator)
    {
        $this->separator = $separator;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function filter($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1' . addcslashes($this->separator, '$') . '$2', $string));
    }

}
