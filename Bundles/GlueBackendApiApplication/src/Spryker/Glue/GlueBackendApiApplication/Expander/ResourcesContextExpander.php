<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;

class ResourcesContextExpander implements ContextExpanderInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    protected $resourcePlugins = [];

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     */
    public function __construct(array $resourcePlugins)
    {
        $this->resourcePlugins = $resourcePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        foreach ($this->resourcePlugins as $resourcePlugin) {
            $resourceContextTransfer = new ResourceContextTransfer();
            $resourceContextTransfer->setResourcePluginName(get_class($resourcePlugin));
            $resourceContextTransfer->setResourceType($resourcePlugin->getType());
            $resourceContextTransfer->setController($resourcePlugin->getController());
            $resourceContextTransfer->setDeclaredMethods($resourcePlugin->getDeclaredMethods());

            $apiApplicationSchemaContextTransfer->addResourceContext($resourceContextTransfer);
        }

        return $apiApplicationSchemaContextTransfer;
    }
}
