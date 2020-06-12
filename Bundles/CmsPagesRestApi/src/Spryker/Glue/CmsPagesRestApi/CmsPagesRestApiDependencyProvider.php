<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi;

use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsPageSearchClientBridge;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToCmsStorageClientBridge;
use Spryker\Glue\CmsPagesRestApi\Dependency\Client\CmsPagesRestApiToStoreClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig getConfig()
 */
class CmsPagesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_STORAGE = 'CLIENT_CMS_STORAGE';
    public const CLIENT_CMS_PAGE_SEARCH = 'CLIENT_CMS_PAGE_SEARCH';
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCmsStorageClient($container);
        $container = $this->addCmsPageSearchClient($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCmsStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_STORAGE, function (Container $container) {
            return new CmsPagesRestApiToCmsStorageClientBridge(
                $container->getLocator()->cmsStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCmsPageSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_CMS_PAGE_SEARCH, function (Container $container) {
            return new CmsPagesRestApiToCmsPageSearchClientBridge(
                $container->getLocator()->cmsPageSearch()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new CmsPagesRestApiToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }
}
