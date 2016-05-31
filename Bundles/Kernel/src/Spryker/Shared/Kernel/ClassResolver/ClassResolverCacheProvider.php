<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

class ClassResolverCacheProvider
{

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassResolverCacheInterface
     */
    protected static $cache;

    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ClassResolverCacheInterface
     */
    protected static function getCache()
    {
        if (self::$cache === null) {
            self::$cache = new ClassResolverCache();
        }

        return self::$cache;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function classExists($className)
    {
        return $this->getCache()->classExists($className);
    }

    /**
     * @return void
     */
    public static function persist()
    {
        self::getCache()->persistCache();
    }

}
