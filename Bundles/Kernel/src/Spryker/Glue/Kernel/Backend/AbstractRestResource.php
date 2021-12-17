<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend;

use Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException;
use Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver;

class AbstractRestResource
{
    /**
     * @var \Spryker\Glue\Kernel\Backend\AbstractFactory
     */
    protected $factory;

    /**
     * @api
     *
     * @param \Spryker\Glue\Kernel\Backend\AbstractFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Glue\Kernel\Backend\AbstractFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidAbstractFactoryException
     *
     * @return \Spryker\Glue\Kernel\Backend\AbstractFactory
     */
    private function resolveFactory()
    {
        $factory = $this->getFactoryResolver()->resolve($this);

        if (!$factory instanceof AbstractFactory) {
            throw new InvalidAbstractFactoryException(sprintf(
                'Modules implementing a %s need to extend the %s',
                static::class,
                AbstractFactory::class,
            ));
        }

        return $factory;
    }

    /**
     * @return \Spryker\Glue\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }
}
