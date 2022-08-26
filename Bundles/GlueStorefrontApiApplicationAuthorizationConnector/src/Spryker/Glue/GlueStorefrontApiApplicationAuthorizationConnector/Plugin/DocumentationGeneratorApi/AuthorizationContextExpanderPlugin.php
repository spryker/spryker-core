<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorClientInterface getClient()
 * @method \Spryker\Glue\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorFactory getFactory()
 */
class AuthorizationContextExpanderPlugin extends AbstractPlugin implements ContextExpanderPluginInterface
{
 /**
  * {@inheritDoc}
  * - Adds the information about protected paths to the documentation generation context.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
  *
  * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
  */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        return $this->getClient()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);
    }
}
