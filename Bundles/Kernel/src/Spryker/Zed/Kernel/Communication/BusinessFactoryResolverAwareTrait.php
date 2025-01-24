<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;

trait BusinessFactoryResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected $businessFactory;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $businessFactory
     *
     * @return $this
     */
    public function setBusinessFactory(AbstractBusinessFactory $businessFactory)
    {
        $this->businessFactory = $businessFactory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected function getBusinessFactory()
    {
        if ($this->businessFactory === null) {
            $this->businessFactory = $this->resolveBusinessFactory();
        }

        return $this->businessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    private function resolveBusinessFactory()
    {
        /** @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $businessFactory */
        $businessFactory = $this->getBusinessFactoryResolver()->resolve($this);

        return $businessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver
     */
    private function getBusinessFactoryResolver()
    {
        return new BusinessFactoryResolver();
    }
}
