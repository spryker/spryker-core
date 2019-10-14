<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Console;

use Spryker\Shared\Kernel\Console\Console as ConsoleConsole;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver;

/**
 * @method \Symfony\Component\Console\Application getApplication()
 */
class Console extends ConsoleConsole
{
    /**
     * @var \Spryker\Yves\Kernel\AbstractFactory|null
     */
    private $factory;

    /**
     * @param \Spryker\Yves\Kernel\AbstractFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    protected function getFactory(): AbstractFactory
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractFactory
     */
    private function resolveFactory(): AbstractFactory
    {
        /** @var \Spryker\Yves\Kernel\AbstractFactory $factory */
        $factory = $this->getFactoryResolver()->resolve($this);

        return $factory;
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver(): FactoryResolver
    {
        return new FactoryResolver();
    }
}
