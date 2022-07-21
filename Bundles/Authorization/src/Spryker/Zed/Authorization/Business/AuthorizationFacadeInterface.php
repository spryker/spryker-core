<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;

interface AuthorizationFacadeInterface
{
    /**
     * Specification:
     * - Performs an authorization check using one AuthorizationStrategyPlugin by strategy name on an authorization request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): AuthorizationResponseTransfer;
}
