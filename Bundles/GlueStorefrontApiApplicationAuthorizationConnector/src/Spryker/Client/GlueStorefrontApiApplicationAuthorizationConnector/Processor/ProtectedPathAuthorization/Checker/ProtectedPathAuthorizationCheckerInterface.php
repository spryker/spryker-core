<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;

interface ProtectedPathAuthorizationCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\RouteTransfer $routeTransfer
     *
     * @return bool
     */
    public function isProtected(RouteTransfer $routeTransfer): bool;
}
