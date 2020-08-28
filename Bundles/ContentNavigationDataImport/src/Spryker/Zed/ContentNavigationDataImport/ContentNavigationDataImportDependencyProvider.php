<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentNavigationDataImport;

use Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentBridge;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeBridge;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Service\ContentNavigationDataImportToUtilEncodingBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentNavigationDataImport\ContentNavigationDataImportConfig getConfig()
 */
class ContentNavigationDataImportDependencyProvider extends DataImportDependencyProvider
{
    public const FACADE_CONTENT_NAVIGATION = 'FACADE_CONTENT_NAVIGATION';
    public const FACADE_CONTENT = 'FACADE_CONTENT';
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
        $container = $this->addContentNavigationFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentNavigationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONTENT_NAVIGATION, function (Container $container) {
            return new ContentNavigationDataImportToContentNavigationFacadeBridge(
                $container->getLocator()->contentNavigation()->facade()
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
            return new ContentNavigationDataImportToContentBridge(
                $container->getLocator()->content()->facade()
            );
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
            return new ContentNavigationDataImportToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
