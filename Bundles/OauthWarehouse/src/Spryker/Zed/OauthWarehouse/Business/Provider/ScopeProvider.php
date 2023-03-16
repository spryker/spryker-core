<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Provider;

use Generated\Shared\Transfer\OauthScopeRequestTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

class ScopeProvider implements ScopeProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig
     */
    protected OauthWarehouseConfig $oauthWarehouseConfig;

    /**
     * @param \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig $oauthWarehouseConfig
     */
    public function __construct(OauthWarehouseConfig $oauthWarehouseConfig)
    {
        $this->oauthWarehouseConfig = $oauthWarehouseConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeRequestTransfer $oauthScopeRequestTransfer
     *
     * @return list<\Generated\Shared\Transfer\OauthScopeTransfer>
     */
    public function getScopes(OauthScopeRequestTransfer $oauthScopeRequestTransfer): array
    {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\OauthScopeTransfer> $oauthScopeTransfers */
        $oauthScopeTransfers = $oauthScopeRequestTransfer->getDefaultScopes();
        /** @var list<\Generated\Shared\Transfer\OauthScopeTransfer> $scopes $scopes */
        $scopes = $oauthScopeTransfers->getArrayCopy();
        foreach ($this->oauthWarehouseConfig->getWarehouseScopes() as $scope) {
            $scopes[] = (new OauthScopeTransfer())->setIdentifier($scope);
        }

        return $scopes;
    }
}
