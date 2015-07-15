<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures;

class Foo
{

    /**
     * @var null|mixed
     */
    private $data;

    /**
     * @param null|mixed $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

}
