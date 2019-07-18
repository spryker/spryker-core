<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetCmsBlockConnector;

use Spryker\Yves\CmsContentWidgetCmsBlockConnector\Dependency\Client\CmsContentWidgetCmsBlockConnectorToCmsBlockStorageClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsContentWidgetCmsBlockConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_BLOCK_STORAGE = 'CLIENT_CMS_BLOCK_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCmsBlockStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCmsBlockStorageClient(Container $container): Container
    {
        $container[static::CLIENT_CMS_BLOCK_STORAGE] = function (Container $container) {
            return new CmsContentWidgetCmsBlockConnectorToCmsBlockStorageClientBridge($container->getLocator()->cmsBlockStorage()->client());
        };

        return $container;
    }
}
