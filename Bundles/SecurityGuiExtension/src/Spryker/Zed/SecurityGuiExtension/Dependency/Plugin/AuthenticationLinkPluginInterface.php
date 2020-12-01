<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;

/**
 * Use this plugin when a third party authentication link needs to be added to the back office login page. 
 */
interface AuthenticationLinkPluginInterface
{
    /**
     * Specification:
     * - Provides data that is necessary to render an authorization link.
     * - Returned property "state" contributes to identify the current Oauth client during the response.
     *
     * @api
     *
     * @see \Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface::isApplicable()
     *
     * @return \Generated\Shared\Transfer\OauthAuthenticationLinkTransfer
     */
    public function getAuthenticationLink(): OauthAuthenticationLinkTransfer;

    /**
     * Specification:
     * - Returns template for custom link rendering.
     *
     * @api
     *
     * @return string|null
     */
    public function getTemplate(): ?string;
}
