<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Client\ContentBanner\Executor\BannerTermExecutor;
use Spryker\Client\ContentBanner\Executor\ContentTermExecutorInterface;
use Spryker\Client\ContentBanner\TermQuery\BannerTermQuery;
use Spryker\Client\ContentBanner\TermQuery\BannerTermQueryInterface;
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
     * @return \Spryker\Client\ContentBanner\TermQuery\BannerTermQueryInterface
     */
    public function createBannerTermQuery(): BannerTermQueryInterface
    {
        return new BannerTermQuery();
    }

    /**
     * @return \Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentBannerToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentBannerDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
