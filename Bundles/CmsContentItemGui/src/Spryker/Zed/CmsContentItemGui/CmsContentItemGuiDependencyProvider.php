<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentItemGui;

use Orm\Zed\CmsContentPlan\Persistence\Base\SpyCmsContentPlanQuery;
use Spryker\Zed\CmsContentItemGui\Dependency\Service\CmsContentItemGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsContentItemGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_CMS_CONTENT_PLAN = 'PROPEL_CMS_CONTENT_PLAN';
    public const UTIL_DATE_TIME_SERVICE = 'UTIL_DATE_TIME_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addPropelCmsContentPlanQuery($container);
        $container = $this->addUtilDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCmsContentPlanQuery(Container $container): Container
    {
        $container[static::PROPEL_CMS_CONTENT_PLAN] = function () {
            return SpyCmsContentPlanQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container[static::UTIL_DATE_TIME_SERVICE] = function (Container $container) {
            return new CmsContentItemGuiToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        };

        return $container;
    }
}
