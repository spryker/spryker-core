<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetContentItemConnector;

use Spryker\Zed\CmsContentWidgetContentItemConnector\Dependency\Client\CmsContentWidgetContentItemConnectorToContentStorageClientBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsContentWidgetContentItemConnector\CmsContentWidgetContentItemConnectorConfig getConfig()
 */
class CmsContentWidgetContentItemConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTENT_STORAGE = 'content storage client';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addContentStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addContentStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONTENT_STORAGE, function (Container $container) {
            return new CmsContentWidgetContentItemConnectorToContentStorageClientBridge(
                $container->getLocator()->contentStorage()->client()
            );
        });

        return $container;
    }
}
