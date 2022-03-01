<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector;

use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Collection\ResourceRelationshipCollection;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class GlueStorefrontApiApplicationGlueJsonApiConventionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_RESOURCE_RELATIONSHIP = 'PLUGINS_RESOURCE_RELATIONSHIP';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addResourceRelationshipPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRelationshipPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RESOURCE_RELATIONSHIP, function (Container $container) {
            return $this->getResourceRelationshipPlugins(new ResourceRelationshipCollection());
        });

        return $container;
    }

    /**
     * Rest resource relation provider plugin collection, plugins must construct full resource by resource ids.
     *
     * @param \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     *
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipPlugins(
        ResourceRelationshipCollectionInterface $resourceRelationshipCollection
    ): ResourceRelationshipCollectionInterface {
        return $resourceRelationshipCollection;
    }
}
