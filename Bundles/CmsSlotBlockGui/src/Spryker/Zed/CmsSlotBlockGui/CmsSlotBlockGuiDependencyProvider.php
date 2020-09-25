<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsBlockFacadeBridge;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeBridge;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig getConfig()
 */
class CmsSlotBlockGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CMS_SLOT_BLOCK = 'FACADE_CMS_SLOT_BLOCK';
    public const FACADE_CMS_SLOT = 'FACADE_CMS_SLOT';
    public const FACADE_CMS_BLOCK = 'FACADE_CMS_BLOCK';

    public const PROPEL_QUERY_CMS_BLOCK = 'PROPEL_QUERY_CMS_BLOCK';
    public const CMS_SLOT_BLOCK_FORM_PLUGINS = 'CMS_SLOT_BLOCK_FORM_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addCmsSlotBlockFacade($container);
        $container = $this->addCmsSlotFacade($container);
        $container = $this->addCmsBlockFacade($container);
        $container = $this->addCmsBlockPropelQuery($container);
        $container = $this->addCmsSlotBlockFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCmsSlotBlockFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotBlockFacade(Container $container): Container
    {
        $container->set(static::FACADE_CMS_SLOT_BLOCK, function (Container $container) {
            return new CmsSlotBlockGuiToCmsSlotBlockFacadeBridge($container->getLocator()->cmsSlotBlock()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotFacade(Container $container): Container
    {
        $container->set(static::FACADE_CMS_SLOT, function (Container $container) {
            return new CmsSlotBlockGuiToCmsSlotFacadeBridge($container->getLocator()->cmsSlot()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsBlockFacade(Container $container): Container
    {
        $container->set(static::FACADE_CMS_BLOCK, function (Container $container) {
            return new CmsSlotBlockGuiToCmsBlockFacadeBridge($container->getLocator()->cmsBlock()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsBlockPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CMS_BLOCK, $container->factory(function () {
            return SpyCmsBlockQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotBlockFormPlugins(Container $container): Container
    {
        $container->set(static::CMS_SLOT_BLOCK_FORM_PLUGINS, function () {
            return $this->getCmsSlotBlockFormPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlockGuiExtension\Communication\Plugin\CmsSlotBlockGuiConditionFormPluginInterface[]
     */
    protected function getCmsSlotBlockFormPlugins(): array
    {
        return [];
    }
}
