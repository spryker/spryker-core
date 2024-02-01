<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthUserConnector\Business\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig;

class BackofficeUserOauthScopeAuthorizationChecker implements BackofficeUserOauthScopeAuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @var \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig
     */
    protected OauthUserConnectorConfig $oauthUserConnectorConfig;

    /**
     * @param \Spryker\Zed\OauthUserConnector\OauthUserConnectorConfig $oauthUserConnectorConfig
     */
    public function __construct(OauthUserConnectorConfig $oauthUserConnectorConfig)
    {
        $this->oauthUserConnectorConfig = $oauthUserConnectorConfig;
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
            return false;
        }

        $authorizedOauthScopes = $requestData[static::GLUE_REQUEST_USER]->getScopes();

        return $this->isBackofficeUserScopeProvided($authorizedOauthScopes)
            || $this->oauthUserConnectorConfig->getUserScopes() === $authorizedOauthScopes;
    }

    /**
     * @param list<string> $authorizedOauthScopes
     *
     * @return bool
     */
    protected function isBackofficeUserScopeProvided(array $authorizedOauthScopes): bool
    {
        return in_array($this->oauthUserConnectorConfig->getBackOfficeUserScope(), $authorizedOauthScopes, true);
    }
}
