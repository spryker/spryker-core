<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;

class OauthUserScopeAuthorizationChecker implements OauthUserScopeAuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @var list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface>
     */
    protected array $userTypeOauthScopeAuthorizationCheckerPlugins;

    /**
     * @param list<\Spryker\Zed\OauthUserConnectorExtension\Dependency\Plugin\UserTypeOauthScopeAuthorizationCheckerPluginInterface> $userTypeOauthScopeAuthorizationCheckerPlugins
     */
    public function __construct(array $userTypeOauthScopeAuthorizationCheckerPlugins)
    {
        $this->userTypeOauthScopeAuthorizationCheckerPlugins = $userTypeOauthScopeAuthorizationCheckerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        $requestData = $authorizationRequestTransfer->getEntityOrFail()->getData();
        if (empty($requestData[static::GLUE_REQUEST_USER])) {
            return true;
        }

        foreach ($this->userTypeOauthScopeAuthorizationCheckerPlugins as $userTypeOauthScopeAuthorizationCheckerPlugin) {
            if ($userTypeOauthScopeAuthorizationCheckerPlugin->authorize($authorizationRequestTransfer)) {
                return true;
            }
        }

        return false;
    }
}
