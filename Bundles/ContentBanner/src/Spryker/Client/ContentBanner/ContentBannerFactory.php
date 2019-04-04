<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Client\ContentBanner\Executor\BannerTermExecutor;
use Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor;
use Spryker\Client\ContentBanner\Executor\BannerTypeExecutorInterface;
use Spryker\Client\ContentBanner\Executor\ContentTermExecutorInterface;
use Spryker\Client\ContentBanner\Resolver\ContentBannerTermResolver;
use Spryker\Client\ContentBanner\Resolver\ContentBannerTermResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ContentBanner\ContentBannerConfig getConfig()
 */
class ContentBannerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentBanner\Resolver\ContentBannerTermResolverInterface
     */
    public function createContentBannerTermResolver(): ContentBannerTermResolverInterface
    {
        return new ContentBannerTermResolver($this->getConfig());
    }

    /**
     * @return \Spryker\Client\ContentBanner\Executor\ContentTermExecutorInterface
     */
    public function createBannerTermExecutor(): ContentTermExecutorInterface
    {
        return new BannerTermExecutor();
    }

    /**
     * @return \Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor
     */
    public function createBannerTermToBannerTypeExecutor(): BannerTypeExecutorInterface
    {
        return new BannerTermToBannerTypeExecutor();
    }

    /**
     * @return \Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentBannerToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentBannerDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
