<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cache\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Cache\Business\CacheBusinessFactory getFactory()
 * @method \Spryker\Zed\Cache\CacheConfig getConfig()
 */
class CacheFacade extends AbstractFacade implements CacheFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use emptyCache() instead
     *
     * @return string[]
     */
    public function deleteAllFiles()
    {
        return $this->getFactory()->createCacheDelete()->deleteAllFiles();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use clearCodeBucketCache() instead.
     *
     * @return string[]
     */
    public function emptyCache()
    {
        return $this->getFactory()->createCacheClearer()->clearCache();
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @return string
     */
    public function emptyCodeBucketCache(): string
    {
        return $this->getFactory()->createCacheClearer()->clearCodeBucketCache();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use emptyAutoLoaderCache() instead
     *
     * @return string[]
     */
    public function deleteAllAutoloaderFiles()
    {
        return $this->getFactory()->createAutoloaderCacheDelete()->deleteAllFiles();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function emptyAutoLoaderCache()
    {
        return $this->getFactory()->createCacheClearer()->clearAutoLoaderCache();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function emptyCodeBucketAutoloaderCache(): string
    {
        return $this->getFactory()->createCacheClearer()->clearCodeBucketAutoLoaderCache();
    }
}
