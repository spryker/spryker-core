<?php

namespace SprykerTest\Shared\Kernel\ClassResolver\Fixtures;

class Foo
{
    /**
     * @var mixed|null
     */
    private $data;

    /**
     * @param mixed|null $data
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
