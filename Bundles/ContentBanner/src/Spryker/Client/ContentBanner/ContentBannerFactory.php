<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor;
use Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutorInterface;
use Spryker\Client\ContentBanner\Executor\ContentTermExecutorInterface;
use Spryker\Client\ContentBanner\TermExecutor\BannerTermExecutor;
use Spryker\Client\Kernel\AbstractFactory;

class ContentBannerFactory extends AbstractFactory
{
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
    public function createBannerTermToBannerTypeExecutor(): BannerTermToBannerTypeExecutorInterface
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
