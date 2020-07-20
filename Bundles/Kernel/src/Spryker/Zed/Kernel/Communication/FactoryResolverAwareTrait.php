<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver;

trait FactoryResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|null
     */
    protected $factory;

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractCommunicationFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory(): AbstractCommunicationFactory
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function resolveFactory(): AbstractCommunicationFactory
    {
        /** @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $resolvedFactory */
        $resolvedFactory = $this->getFactoryResolver()->resolve($this);

        return $resolvedFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Communication\CommunicationFactoryResolver
     */
    private function getFactoryResolver()
    {
        return new CommunicationFactoryResolver();
    }
}
