<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthUserTransfer;

interface OauthWarehouseFacadeInterface
{
    /**
     * Specification:
     * - Returns warehouse scopes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array;

    /**
     * Specification:
     *  - Installs warehouse oauth scope data.
     *
     * @api
     *
     * @return void
     */
    public function installWarehouseOauthData(): void;

    /**
     * Specification:
     * - Returns true if the request identity is user.
     * - Returns true if the request identity is warehouse, and it's valid.
     * - Returns false in other cases.
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
     * - Retrieves warehouse user if `OauthUserTransfer.idWarehouse` provided.
     * - Expands the `OauthUserTransfer` if warehouse user exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthWarehouseUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer;
}
