<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Config;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;

class SharedConfigResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'SharedConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Config\SharedConfigNotFoundException
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Shared\Kernel\AbstractSharedConfig $resolved */
        $resolved = parent::doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new SharedConfigNotFoundException($this->getClassInfo());
    }
}
