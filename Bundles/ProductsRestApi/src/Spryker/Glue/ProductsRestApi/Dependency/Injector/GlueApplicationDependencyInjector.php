<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Dependency\Injector;

use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Glue\ProductsRestApi\Plugin\GlueApplication\ConcreteProductsByProductConcreteIdsResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class GlueApplicationDependencyInjector implements DependencyInjectorInterface
{
    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function inject(Container $container): Container
    {
        $container = $this->injectConcreteProductsByProductConcreteIdsResourceRelationshipPlugin($container);

        return $container;
    }

    /**
     * @deprecated Will be removed in the next major version. Adding the relationship will be moved to GlueApplicationDependencyProvider thus making the relationship optional.
     *
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function injectConcreteProductsByProductConcreteIdsResourceRelationshipPlugin(Container $container): Container
    {
        $container->extend(GlueApplicationDependencyProvider::PLUGIN_RESOURCE_RELATIONSHIP, function (ResourceRelationshipCollectionInterface $resourceRelationshipCollection) {
            $resourceRelationshipCollection->addRelationship(
                ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                new ConcreteProductsByProductConcreteIdsResourceRelationshipPlugin()
            );

            return $resourceRelationshipCollection;
        });

        return $container;
    }
}
