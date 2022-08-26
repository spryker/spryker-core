<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContextExpanderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorFacadeInterface getFacade()
 * @method \Spryker\Glue\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorFactory getFactory()
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
        return $this->getFacade()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);
    }
}
