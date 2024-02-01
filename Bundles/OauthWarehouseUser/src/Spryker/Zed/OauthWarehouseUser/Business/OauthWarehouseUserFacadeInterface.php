<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;

interface OauthWarehouseUserFacadeInterface
{
    /**
     * Specification:
     * - Provides OAuth scopes related to warehouse users.
     * - Requires `UserIdentifierTransfer.idUser` be set.
     * - Returns list with OAuth scopes if provided user is a warehouse user otherwise it is empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getWarehouseUserTypeOauthScopes(UserIdentifierTransfer $userIdentifierTransfer): array;

    /**
     * Specification:
     * - Authorizes user by warehouse user scopes.
     * - Requires `AuthorizationRequestTransfer.entity` to be set.
     * - Returns false if the request lacks user identity or essential request data.
     * - Returns false if the authenticated user lacks the required warehouse user scope.
     * - Checks if the requested path is found by the fully qualified path name in an array of allowed paths.
     * - Checks if the requested path is found by a regular expression in an array of allowed paths.
     * - Returns false if the path is not found in the allowed paths otherwise, the user is authorized.
     * - Returns true if the path is found in the allowed paths; the user is authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorizeByWarehouseUserScope(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;
}
