<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
     */
    public function getData()
    {
        return $this->data;
    }

}
