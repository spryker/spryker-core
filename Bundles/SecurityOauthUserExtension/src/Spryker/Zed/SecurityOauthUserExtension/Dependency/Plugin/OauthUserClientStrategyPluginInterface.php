<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;

/**
 * Use this plugin to provide Oauth user authentication clients.
 */
interface OauthUserClientStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for provided ResourceOwnerRequest.
     * - Hint: mostly the check should be done by the query parameter "state".
     *
     * @api
     *
     * @see \Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationLinkPluginInterface::getAuthenticationLink()
     *
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): bool;

    /**
     * Specification:
     * - Requests a resource owner using a specified option set.
     * - An additional options can be passed to the underlying provider via resource owner request transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceOwnerResponseTransfer
     */
    public function getResourceOwner(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): ResourceOwnerResponseTransfer;
}
