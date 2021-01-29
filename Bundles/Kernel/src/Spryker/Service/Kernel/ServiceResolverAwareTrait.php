<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver;

trait ServiceResolverAwareTrait
{
    /**
     * @var \Spryker\Service\Kernel\AbstractService
     */
    protected $service;

    /**
     * @param \Spryker\Service\Kernel\AbstractService $service
     *
     * @return $this
     */
    public function setService(AbstractService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    protected function getService()
    {
        if ($this->service === null) {
            $this->service = $this->resolveService();
        }

        return $this->service;
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractService
     */
    private function resolveService()
    {
        return $this->getServiceResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Service\Kernel\ClassResolver\Service\ServiceResolver
     */
    private function getServiceResolver()
    {
        return new ServiceResolver();
    }
}
