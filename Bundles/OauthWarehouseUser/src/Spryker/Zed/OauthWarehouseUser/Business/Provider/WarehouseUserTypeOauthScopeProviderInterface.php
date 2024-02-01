<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouseUser\Business\Provider;

use Generated\Shared\Transfer\UserIdentifierTransfer;

interface WarehouseUserTypeOauthScopeProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserIdentifierTransfer $userIdentifierTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(UserIdentifierTransfer $userIdentifierTransfer): array;
}
