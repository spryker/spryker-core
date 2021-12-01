<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Facade;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class FacadeResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ZedFacade';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new FacadeNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
