<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Fixtures\Backend;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\AbstractFactory;
use SprykerTest\Glue\Kernel\Fixtures\ConcreteDependencyProvider;

class ConcreteFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function getInternalContainer()
    {
        return $this->getContainer();
    }

    /**
     * @return \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider(): AbstractBundleDependencyProvider
    {
        return new ConcreteDependencyProvider();
    }
}
