<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsSlotGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPER_QUERY_CMS_SLOT_TEMPLATE = 'PROPER_QUERY_CMS_SLOT_TEMPLATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCmsSlotTemplateQuery($container);

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
}
