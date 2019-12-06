<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Container\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;

class ContainerHelper extends Module
{
    /**
     * @var \Spryker\Service\Container\ContainerInterface|null
     */
    protected $container;

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $this->container = new Container();
        }

        return $this->container;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->container = null;
    }
}
