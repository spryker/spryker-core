<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenReaderPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenReaderPlugin extends AbstractPlugin implements OauthRefreshTokenReaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if refresh token identifier is not empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return bool
     */
    public function isApplicable(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): bool
    {
        return (bool)$oauthTokenCriteriaFilterTransfer->getIdentifier();
    }

    /**
     * {@inheritDoc}
     * - Finds refresh token by provided criteria.
     * - Returns oauth refresh token if refresh token was found and null if not.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer
    {
        return $this->getFacade()->findRefreshToken($oauthTokenCriteriaFilterTransfer);
    }
}
