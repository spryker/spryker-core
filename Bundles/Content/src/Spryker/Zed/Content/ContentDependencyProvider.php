<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content;

use Spryker\Zed\Content\Dependency\External\ContentToValidationAdapter;
use Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Content\ContentConfig getConfig()
 */
class ContentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const ADAPTER_VALIDATION = 'ADAPTER_VALIDATION';
    public const SERVICE_UTIL_UUID_GENERATOR = 'SERVICE_UTIL_UUID_GENERATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addValidationAdapter($container);
        $container = $this->addUtilUuidGenerator($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addValidationAdapter(Container $container): Container
    {
        $container->set(static::ADAPTER_VALIDATION, function () {
            return new ContentToValidationAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilUuidGenerator(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_UUID_GENERATOR, function (Container $container) {
            return new ContentToUtilUuidGeneratorServiceBridge(
                $container->getLocator()->utilUuidGenerator()->service()
            );
        });

        return $container;
    }
}
