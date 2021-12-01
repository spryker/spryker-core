<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\Config;

use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'YvesConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Yves\Kernel\AbstractBundleConfig|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new BundleConfigNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
