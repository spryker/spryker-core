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
     * @deprecated Use {@link emptyCache()} instead
     *
     * @return array<string>
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
     * @deprecated Use {@link emptyCodeBucketCache()} instead.
     *
     * @return array<string>
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
        return $this->getFactory()
            ->createCacheClearer()
            ->clearCodeBucketCache();
    }

    /**
     * @inheritDoc
     *
     * @api
     *
     * @return string
     */
    public function emptyDefaultCodeBucketCache(): string
    {
        return $this->getFactory()
            ->createCacheClearer()
            ->clearDefaultCodeBucketCache();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link emptyAutoLoaderCache()} instead
     *
     * @return array<string>
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
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return array<string>
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
     * @return array<string>
     */
    public function emptyProjectSpecificCache(): array
    {
        return $this->getFactory()->createCacheClearer()->clearProjectSpecificCache();
    }
}
