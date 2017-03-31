<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;

class Dependency extends Module
{

    /**
     * @var \Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals
     */
    private $containerGlobals;

    /**
     * @return void
     */
    public function _initialize()
    {
        $this->containerGlobals = new ContainerGlobals();
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function setDependency($key, $value)
    {
        $this->containerGlobals[$key] = $value;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        $this->containerGlobals->reset();
    }

}
