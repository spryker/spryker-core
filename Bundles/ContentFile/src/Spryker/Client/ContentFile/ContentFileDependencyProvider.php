<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentFile;

use Spryker\Client\ContentFile\Dependency\Client\ContentFileToContentStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class ContentFileDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_CONTENT_STORAGE = 'CLIENT_CONTENT_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addContentStorage($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addContentStorage(Container $container): Container
    {
        $container[static::CLIENT_CONTENT_STORAGE] = function (Container $container) {
            return new ContentFileToContentStorageClientBridge(
                $container->getLocator()->contentStorage()->client()
            );
        };

        return $container;
    }
}
