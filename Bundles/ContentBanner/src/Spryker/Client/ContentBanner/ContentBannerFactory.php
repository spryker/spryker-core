<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentBanner;

use Spryker\Client\ContentBanner\Dependency\Client\ContentBannerToContentStorageClientInterface;
use Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor;
use Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface;
use Spryker\Client\ContentBanner\Mapper\ContentBannerTypeMapper;
use Spryker\Client\ContentBanner\Mapper\ContentBannerTypeMapperInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ContentBanner\ContentBannerConfig;

class ContentBannerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentBanner\Mapper\ContentBannerTypeMapperInterface
     */
    public function createContentBannerTypeMapper(): ContentBannerTypeMapperInterface
    {
        return new ContentBannerTypeMapper(
            $this->getContentStorageClient(),
            $this->getContentBannerTermExecutorMap()
        );
    }

    /**
     * @return \Spryker\Client\ContentBanner\Executor\ContentBannerTermExecutorInterface[]
     */
    public function getContentBannerTermExecutorMap(): array
    {
        return [
            ContentBannerConfig::CONTENT_TERM_BANNER => $this->createBannerTermToBannerTypeExecutor(),
        ];
    }

    /**
     * @return \Spryker\Client\ContentBanner\Executor\BannerTermToBannerTypeExecutor
     */
    public function createBannerTermToBannerTypeExecutor(): ContentBannerTermExecutorInterface
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
