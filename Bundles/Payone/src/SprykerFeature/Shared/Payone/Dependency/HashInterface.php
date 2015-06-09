<?php

namespace SprykerFeature\Shared\Payone\Dependency;

interface HashInterface
{

    /**
     * @param string $value
     * @return string
     */
    public function hash($value);

}
