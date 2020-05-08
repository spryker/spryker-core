<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokensReaderPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokensReaderPlugin extends AbstractPlugin implements OauthRefreshTokensReaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets refresh tokens by provided criteria.
     * - Returns oauth refresh tokens collection.
     * - Returns the empty collection in case there is no refresh tokens.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function getRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer
    {
        return $this->getFacade()->getRefreshTokens($oauthTokenCriteriaFilterTransfer);
    }
}
