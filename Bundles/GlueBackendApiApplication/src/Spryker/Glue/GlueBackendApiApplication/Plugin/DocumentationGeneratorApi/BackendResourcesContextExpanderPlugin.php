<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class BackendResourcesContextExpanderPlugin extends AbstractPlugin implements ContextExpanderPluginInterface
{
 /**
  * {@inheritDoc}
  * - Adds resource information to the documentation generation context
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
  *
  * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
  */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        return $this->getFactory()->createResourcesContextExpander()->expand($apiApplicationSchemaContextTransfer);
    }
}
