<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotGui\Communication\Dependency\CmsSlotGuiToCmsSlotFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsSlotGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPER_QUERY_CMS_SLOT_TEMPLATE = 'PROPER_QUERY_CMS_SLOT_TEMPLATE';
    public const PROPER_QUERY_CMS_SLOT = 'PROPER_QUERY_CMS_SLOT';
    public const FACADE_CMS_SLOT = 'FACADE_CMS_SLOT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCmsSlotTemplateQuery($container);
        $container = $this->addCmsSlotQuery($container);
        $container = $this->addCmsSlotFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotTemplateQuery(Container $container): Container
    {
        $container->set(static::PROPER_QUERY_CMS_SLOT_TEMPLATE, function () {
            return SpyCmsSlotTemplateQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotQuery(Container $container): Container
    {
        $container->set(static::PROPER_QUERY_CMS_SLOT, function () {
            return SpyCmsSlotQuery::create();
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
            return new CmsSlotGuiToCmsSlotFacadeBridge($container->getLocator()->cmsSlot()->facade());
        });

        return $container;
    }
}
