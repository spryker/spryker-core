<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi;

use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToCmsStorageClientBridge;
use Spryker\Glue\ContentBannersRestApi\Dependency\Client\ContentBannersRestApiToContentBannerClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiConfig getConfig()
 */
class ContentBannersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTENT_BANNER = 'CLIENT_CONTENT_BANNER';
    public const CLIENT_CMS_STORAGE = 'CLIENT_CMS_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addContentBannerClient($container);
        $container = $this->addCmsStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addContentBannerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONTENT_BANNER, function (Container $container) {
            return new ContentBannersRestApiToContentBannerClientBridge(
                $container->getLocator()->contentBanner()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCmsStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_STORAGE, function (Container $container) {
            return new ContentBannersRestApiToCmsStorageClientBridge(
                $container->getLocator()->cmsStorage()->client()
            );
        });

        return $container;
    }
}
