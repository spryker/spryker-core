<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;

trait FacadeResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected $facade;

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return $this
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade(): AbstractFacade
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function resolveFacade(): AbstractFacade
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver
     */
    private function getFacadeResolver(): FacadeResolver
    {
        return new FacadeResolver();
    }
}
