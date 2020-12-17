<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Communication\Plugin\SecurityOauthUser;

use Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer;
use Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserRestrictionPluginInterface;

/**
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 */
class MerchantUserOauthUserRestrictionPlugin extends AbstractPlugin implements OauthUserRestrictionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `OauthUserRestrictionRequestTransfer.user.username` to be provided.
     * - Checks if the Oauth user is restricted.
     * - When the user has a relation to the merchant he is considered as restricted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserRestrictionResponseTransfer
     */
    public function isRestricted(
        OauthUserRestrictionRequestTransfer $oauthUserRestrictionRequestTransfer
    ): OauthUserRestrictionResponseTransfer {
        return $this->getFacade()->isOauthUserRestricted($oauthUserRestrictionRequestTransfer);
    }
}
