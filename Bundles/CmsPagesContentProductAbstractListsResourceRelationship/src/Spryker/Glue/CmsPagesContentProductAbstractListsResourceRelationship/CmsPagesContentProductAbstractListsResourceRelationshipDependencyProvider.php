<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship;

use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientBridge;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientBridge;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\CmsPagesContentProductAbstractListsResourceRelationshipConfig getConfig()
 */
class CmsPagesContentProductAbstractListsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CMS_STORAGE = 'CLIENT_CMS_STORAGE';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_REST_API = 'RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_REST_API';

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
        $container = $this->addContentProductAbstractListsRestApiResource($container);

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
            return new CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientBridge(
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
            return new CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientBridge(
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
    protected function addContentProductAbstractListsRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_REST_API, function (Container $container) {
            return new CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceBridge(
                $container->getLocator()->contentProductAbstractListsRestApi()->resource()
            );
        });

        return $container;
    }
}
