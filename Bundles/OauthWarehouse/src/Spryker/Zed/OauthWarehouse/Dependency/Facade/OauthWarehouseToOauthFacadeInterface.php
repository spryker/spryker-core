<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Dependency\Facade;

use Generated\Shared\Transfer\OauthScopeTransfer;

interface OauthWarehouseToOauthFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    public function saveScope(OauthScopeTransfer $oauthScopeTransfer): OauthScopeTransfer;

    /**
     * @param list<string> $userScopes
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopesByIdentifiers(array $userScopes): array;
}
