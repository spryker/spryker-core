<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;


class ClassResolverCacheHandler extends AbstractClassResolver
{

    /**
     * @return string
     */
    protected function getClassPattern()
    {
        // TODO: Implement getClassPattern() method.
    }

    /**
     * @param string $namespace
     * @param string|null $store
     *
     * @return string
     */
    protected function buildClassName($namespace, $store = null)
    {
        // TODO: Implement buildClassName() method.
    }

    /**
     * @return void
     */
    public static function persistCache()
    {
        self::getCache()->persistCache();
    }
}
