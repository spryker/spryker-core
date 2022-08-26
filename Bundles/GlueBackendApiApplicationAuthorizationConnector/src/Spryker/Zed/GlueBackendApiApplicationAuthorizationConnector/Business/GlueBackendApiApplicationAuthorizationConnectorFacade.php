<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\GlueBackendApiApplicationAuthorizationConnectorBusinessFactory getFactory()
 */
class GlueBackendApiApplicationAuthorizationConnectorFacade extends AbstractFacade implements GlueBackendApiApplicationAuthorizationConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        return $this->getFactory()
            ->createProtectedPathAuthorizationChecker()
            ->authorize($authorizationRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expandApiApplicationSchemaContext(
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): ApiApplicationSchemaContextTransfer {
        return $this->getFactory()->createProtectedPathAuthorizationExpander()->expandApiApplicationSchemaContext($apiApplicationSchemaContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RouteTransfer $routeTransfer
     *
     * @return bool
     */
    public function isProtected(RouteTransfer $routeTransfer): bool
    {
        return $this->getFactory()->createProtectedPathAuthorizationChecker()->isProtected($routeTransfer);
    }
}
