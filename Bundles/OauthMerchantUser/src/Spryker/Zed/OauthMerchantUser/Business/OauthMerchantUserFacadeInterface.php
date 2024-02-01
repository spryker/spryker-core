<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\UserIdentifierTransfer;

interface OauthMerchantUserFacadeInterface
{
    /**
     * Specification:
     * - Provides OAuth scopes related to merchant users.
     * - Requires `UserIdentifierTransfer.idUser` be set.
     * - Returns list with OAuth scopes if provided user is a merchant user otherwise it is empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getMerchantUserTypeOauthScopes(UserIdentifierTransfer $userIdentifierTransfer): array;

    /**
     * Specification:
     * - Authorizes user by merchant user scopes.
     * - Requires `AuthorizationRequestTransfer.entity` to be set.
     * - Returns false if the request lacks user identity or essential request data.
     * - Returns false if the authenticated user lacks the required merchant user scope.
     * - Checks if the requested path is included in the list of allowed paths.
     * - Checks if the requested path matches any regular expressions in the list of allowed paths.
     * - Returns false if the path is not found in the allowed paths otherwise, the user is authorized.
     * - Returns true if the path is found in the allowed paths; the user is authorized.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorizeByMerchantUserScope(AuthorizationRequestTransfer $authorizationRequestTransfer): bool;
}
