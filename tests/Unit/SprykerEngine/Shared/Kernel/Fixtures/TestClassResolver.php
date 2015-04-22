<?php

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

class TestClassResolver
{

    /**
     * @var null
     */
    protected $data;

    /**
     * @param null $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getData()
    {
        return $this->data;
    }
}
