<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Expander\ContextExpanderInterface;
use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Expander\RelationshipPluginsContextExpander;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;

class GlueBackendApiApplicationGlueJsonApiConventionConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Expander\ContextExpanderInterface
     */
    public function createRelationshipPluginsContextExpander(): ContextExpanderInterface
    {
        return new RelationshipPluginsContextExpander($this->getResourceRelationshipPlugins());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    public function getResourceRelationshipPlugins(): ResourceRelationshipCollectionInterface
    {
        return $this->getProvidedDependency(GlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider::PLUGINS_RESOURCE_RELATIONSHIP);
    }
}
