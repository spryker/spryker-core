<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

class Bar
{
    /**
     * @var mixed
     */
    protected $foo;

    /**
     * @var mixed
     */
    protected $bar;

    /**
     * @param mixed $foo
     * @param mixed $bar
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
