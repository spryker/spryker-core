<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel\ClassResolver\Config;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ClientConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Client\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Client\Kernel\AbstractBundleConfig|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new BundleConfigNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
