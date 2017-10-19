<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

interface ResolverCacheFactoryInterface
{
    /**
     * @return bool
     */
    public function useCache();

    /**
     * @throws \Exception
     *
     * @return \Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface
     */
    public function createClassResolverCacheProvider();
}
