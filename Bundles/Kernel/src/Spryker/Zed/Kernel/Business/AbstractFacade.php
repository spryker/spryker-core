<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\EntityManagerResolverAwareTrait;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;

abstract class AbstractFacade
{
    use EntityManagerResolverAwareTrait;
    use RepositoryResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    private $factory;

    /**
     * @api
     *
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractBusinessFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }
}
