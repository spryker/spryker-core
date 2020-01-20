<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCmsGui;

use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToLocaleFacadeBridge;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Facade\CmsSlotBlockCmsGuiToTranslatorFacadeBridge;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\QueryContainer\CmsSlotBlockCmsGuiToCmsQueryContainerBridge;
use Spryker\Zed\CmsSlotBlockCmsGui\Dependency\Service\CmsSlotBlockCmsGuiToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsSlotBlockCmsGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_CMS = 'QUERY_CONTAINER_CMS';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCmsQueryContainer($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_CMS, function (Container $container) {
            return new CmsSlotBlockCmsGuiToCmsQueryContainerBridge($container->getLocator()->cms()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new CmsSlotBlockCmsGuiToTranslatorFacadeBridge(
                $container->getLocator()->translator()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CmsSlotBlockCmsGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncoding(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CmsSlotBlockCmsGuiToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
