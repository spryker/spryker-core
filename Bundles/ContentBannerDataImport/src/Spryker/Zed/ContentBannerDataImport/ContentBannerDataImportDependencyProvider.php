<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport;

use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerBridge;
use Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig getConfig()
 */
class ContentBannerDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_CONTENT_BANNER = 'FACADE_CONTENT_BANNER';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addContentBannerFacade($container);
        $container = $this->provideUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentBannerFacade(Container $container): Container
    {
        $container[static::FACADE_CONTENT_BANNER] = function (Container $container) {
            return new ContentBannerDataImportToContentBannerBridge($container->getLocator()->contentBanner()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ContentBannerDataImportToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
