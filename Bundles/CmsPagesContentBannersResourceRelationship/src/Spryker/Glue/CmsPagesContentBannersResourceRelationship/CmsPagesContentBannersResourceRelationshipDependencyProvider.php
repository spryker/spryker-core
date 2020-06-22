<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship;

use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToCmsStorageClientBridge;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\Client\CmsPagesContentBannersResourceRelationshipToStoreClientBridge;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource\CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CmsPagesContentBannersResourceRelationship\CmsPagesContentBannersResourceRelationshipConfig getConfig()
 */
class CmsPagesContentBannersResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_STORAGE = 'CLIENT_CMS_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const RESOURCE_CONTENT_BANNERS_REST_API = 'RESOURCE_CONTENT_BANNERS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addCmsStorageClient($container);
        $container = $this->addStoreClient($container);
        $container = $this->addContentBannersRestApiResource($container);

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
            return new CmsPagesContentBannersResourceRelationshipToCmsStorageClientBridge(
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
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new CmsPagesContentBannersResourceRelationshipToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addContentBannersRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_CONTENT_BANNERS_REST_API, function (Container $container) {
            return new CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceBridge(
                $container->getLocator()->contentBannersRestApi()->resource()
            );
        });

        return $container;
    }
}
