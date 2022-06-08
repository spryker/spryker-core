<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class StorefrontResourcesContextExpanderPlugin extends AbstractPlugin implements ContextExpanderPluginInterface
{
 /**
  * {@inheritDoc}
  * - Adds resource information to the documentation generation context.
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
