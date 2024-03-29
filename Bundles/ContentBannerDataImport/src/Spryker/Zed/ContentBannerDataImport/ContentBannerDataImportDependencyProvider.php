<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentBannerDataImport;

use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerBridge;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBridge;
use Spryker\Zed\ContentBannerDataImport\Dependency\Service\ContentBannerDataImportToUtilEncodingBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig getConfig()
 */
class ContentBannerDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CONTENT = 'FACADE_CONTENT';

    /**
     * @var string
     */
    public const FACADE_CONTENT_BANNER = 'FACADE_CONTENT_BANNER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addContentFacade($container);
        $container = $this->addContentBannerFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentBannerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONTENT_BANNER, function (Container $container) {
            return new ContentBannerDataImportToContentBannerBridge(
                $container->getLocator()->contentBanner()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONTENT, function (Container $container) {
            return new ContentBannerDataImportToContentBridge($container->getLocator()->content()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ContentBannerDataImportToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }
}
