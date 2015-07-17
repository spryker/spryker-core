<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

class Bar
{

    /**
     * @var mixed
     */
    private $foo;

    /**
     * @var mixed
     */
    private $bar;

    /**
     * @param $foo
     * @param $bar
     */
    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @return mixed
     */
    public function getBar()
    {
        return $this->bar;
    }

}
