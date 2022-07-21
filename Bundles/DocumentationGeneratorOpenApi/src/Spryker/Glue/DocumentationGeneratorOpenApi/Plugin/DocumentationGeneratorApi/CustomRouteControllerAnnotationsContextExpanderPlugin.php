<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiFactory getFactory()
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig getConfig()
 */
class CustomRouteControllerAnnotationsContextExpanderPlugin extends AbstractPlugin implements ContextExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds controller annotations information to the documentation generation context for custom routes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        return $this->getFactory()
            ->createCustomRouteControllerAnnotationsContextExpander()
            ->expand($apiApplicationSchemaContextTransfer);
    }
}
