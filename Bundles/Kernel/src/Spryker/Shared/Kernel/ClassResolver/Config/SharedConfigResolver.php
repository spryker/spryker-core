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
        $resolved = $this->doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
        }

        throw new SharedConfigNotFoundException($this->getClassInfo());
    }

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    protected function buildClassName($namespace, $codeBucket = null)
    {
        $searchAndReplace = [
            self::KEY_NAMESPACE => $namespace,
            self::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        return str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern()
        );
    }
}
