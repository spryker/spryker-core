<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui;

use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeBridge;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeBridge;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToTranslatorFacadeBridge;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Service\CmsSlotBlockCategoryGuiToUtilEncodingBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsSlotBlockCategoryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCategoryFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CmsSlotBlockCategoryGuiToCategoryFacadeBridge(
                $container->getLocator()->category()->facade()
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
            return new CmsSlotBlockCategoryGuiToLocaleFacadeBridge(
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
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return new CmsSlotBlockCategoryGuiToTranslatorFacadeBridge(
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
    protected function addUtilEncoding(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CmsSlotBlockCategoryGuiToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
