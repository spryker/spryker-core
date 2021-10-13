<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthorizationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;

interface AuthorizationStrategyPluginInterface
{
    /**
     * Specification:
     * - Processes an authorization request.
     * - Returns true if authorized, false if not authorized.
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
     * - Returns the strategy name so that the authorization system can look up and use the strategy by name to perform the authorization check against that strategy.
     *
     * @api
     *
     * @return string
     */
    public function getStrategyName(): string;
}
