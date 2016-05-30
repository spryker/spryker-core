<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

interface ClassResolverCacheInterface
{

    /**
     * @param string $className
     *
     * @return bool
     */
    public function classExists($className);

    /**
     * @return void
     */
    public function persistCache();

    /**
     * @return array
     */
    public function getCachedData();

}
