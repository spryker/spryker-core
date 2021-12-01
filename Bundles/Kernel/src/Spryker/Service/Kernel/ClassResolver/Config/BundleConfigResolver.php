<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\Config;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ServiceConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Service\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Service\Kernel\AbstractBundleConfig|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new BundleConfigNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
