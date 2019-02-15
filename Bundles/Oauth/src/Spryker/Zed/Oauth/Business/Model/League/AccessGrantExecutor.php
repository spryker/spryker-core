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
     * @var \Spryker\Zed\Oauth\Business\Model\League\GrantTypeExecutor
     */
    protected $grantTypeExecutor;
    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface[]
     */
    protected $grantTypeProviderPlugins;

    /**
     * @param array $grantTypes
     * @param \Spryker\Zed\Oauth\Business\Model\League\GrantTypeExecutor $grantTypeExecutor
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface[] $grantTypeProviderPlugins
     */
    public function __construct(
        array $grantTypes,
        GrantTypeExecutor $grantTypeExecutor,
        array $grantTypeProviderPlugins
    ) {
        $this->grants = $grantTypes;
        $this->grantTypeExecutor = $grantTypeExecutor;
        $this->grantTypeProviderPlugins = $grantTypeProviderPlugins;
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
