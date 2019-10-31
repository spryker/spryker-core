<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockCategoryGui;

use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToCategoryFacadeBridge;
use Spryker\Zed\CmsSlotBlockCategoryGui\Dependency\Facade\CmsSlotBlockCategoryGuiToLocaleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsSlotBlockCategoryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCategoryFacade($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CmsSlotBlockCategoryGuiToCategoryFacadeBridge(
                $container->getLocator()->category()->facade()
            );
        });
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CmsSlotBlockCategoryGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });
    }
}
