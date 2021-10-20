<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport;

use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentBridge;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeBridge;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig getConfig()
 */
class ContentProductDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CONTENT = 'FACADE_CONTENT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const FACADE_CONTENT_PRODUCT = 'FACADE_CONTENT_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);
        $container = $this->addContentProductFacade($container);
        $container = $this->addContentFacade($container);

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
            return new ContentProductDataImportToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONTENT_PRODUCT, function (Container $container) {
            return new ContentProductDataImportToContentProductFacadeBridge(
                $container->getLocator()->contentProduct()->facade()
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
            return new ContentProductDataImportToContentBridge($container->getLocator()->content()->facade());
        });

        return $container;
    }
}
