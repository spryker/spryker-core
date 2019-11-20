<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals;

class DependencyHelper extends Module
{
    /**
     * @var \Spryker\Shared\Kernel\ContainerMocker\ContainerGlobals
     */
    private $containerGlobals;

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->containerGlobals = new ContainerGlobals();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $onlyFor
     *
     * @return void
     */
    public function setDependency(string $key, $value, ?string $onlyFor = null): void
    {
        $this->containerGlobals->set($key, $value, $onlyFor);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->containerGlobals->reset();
    }
}
