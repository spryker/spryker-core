<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;

interface GlueStorefrontApiApplicationAuthorizationConnectorClientInterface
{
    /**
     * Specification:
     * - Checks if the requested path is found by the fully qualified path name in an array of protected paths.
     * - Checks if the requested path is found by a regular expression in an array of protected paths.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;

    /**
     * Specification:
     * - Adds isProtected property to the endpoint.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expandApiApplicationSchemaContext(
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): ApiApplicationSchemaContextTransfer;

    /**
     * Specification:
     * - Checks if the requested path is protected.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RouteTransfer $routeTransfer
     *
     * @return bool
     */
    public function isProtected(RouteTransfer $routeTransfer): bool;
}
