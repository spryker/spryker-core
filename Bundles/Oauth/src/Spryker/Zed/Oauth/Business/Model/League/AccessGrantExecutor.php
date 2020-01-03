<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

class AccessGrantExecutor implements AccessGrantExecutorInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface[]
     */
    protected $grants;

    /**
     * @param array $grantTypes
     */
    public function __construct(array $grantTypes)
    {
        $this->grants = $grantTypes;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function executeByRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        if (!isset($this->grants[$oauthRequestTransfer->getGrantType()])) {
            $oauthResponseTransfer = new OauthResponseTransfer();
            $oauthErrorTransfer = new OauthErrorTransfer();
            $oauthErrorTransfer->setMessage(sprintf('Grant type "%s" not found', $oauthRequestTransfer->getGrantType()));
            $oauthResponseTransfer->setError($oauthErrorTransfer);

            return $oauthResponseTransfer;
        }
        $grantType = $this->grants[$oauthRequestTransfer->getGrantType()];

        return $grantType->processAccessTokenRequest($oauthRequestTransfer);
    }
}
