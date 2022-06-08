<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector;

use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Expander\ContextExpanderInterface;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Expander\RelationshipPluginsContextExpander;
use Spryker\Glue\Kernel\AbstractFactory;

class GlueStorefrontApiApplicationGlueJsonApiConventionConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceProviderPlugins(): ResourceRelationshipCollectionInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationGlueJsonApiConventionConnectorDependencyProvider::PLUGINS_RESOURCE_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Expander\ContextExpanderInterface
     */
    public function createRelationshipPluginsContextExpander(): ContextExpanderInterface
    {
        return new RelationshipPluginsContextExpander($this->getResourceProviderPlugins());
    }
}
