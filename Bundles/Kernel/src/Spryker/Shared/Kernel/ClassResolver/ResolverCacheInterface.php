<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
 */
interface ResolverCacheInterface
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
    public function persist();

    /**
     * @return array
     */
    public function getData();
}
