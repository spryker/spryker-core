<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\OauthRevoke;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensRevokerPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokensRevokerPlugin extends AbstractPlugin implements OauthRefreshTokensRevokerPluginInterface
{
    /**
     * @api
     *
     * @inheritDoc
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void
    {
        $this->getFacade()->revokeRefreshTokens($oauthRefreshTokenTransfers);
//        $this->oauthEntityManager->revokeAllRefreshTokens($oauthRefreshTokenTransfers);
    }
}
