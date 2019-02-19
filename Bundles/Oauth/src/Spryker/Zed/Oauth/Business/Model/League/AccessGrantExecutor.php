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
     * @var \Spryker\Zed\Oauth\Business\Model\League\GrantTypeExecutorInterface
     */
    protected $grantTypeExecutor;

    /**
     * @var \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface[]
     */
    protected $grantTypeProviderPlugins;

    /**
     * @param array $grantTypes
     * @param \Spryker\Zed\Oauth\Business\Model\League\GrantTypeExecutorInterface $grantTypeExecutor
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface[] $grantTypeProviderPlugins
     */
    public function __construct(
        array $grantTypes,
        GrantTypeExecutorInterface $grantTypeExecutor,
        array $grantTypeProviderPlugins
    ) {
        $this->grants = $grantTypes;
        $this->grantTypeExecutor = $grantTypeExecutor;
        $this->setGrantTypeProviderPlugins($grantTypeProviderPlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function executeByRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        if (isset($this->grants[$oauthRequestTransfer->getGrantType()])) {
            $grantType = $this->grants[$oauthRequestTransfer->getGrantType()];
            return $grantType->processAccessTokenRequest($oauthRequestTransfer);
        }

        if (isset($this->grantTypeProviderPlugins[$oauthRequestTransfer->getGrantType()])) {
            $grantTypeProviderPlugin = $this->grantTypeProviderPlugins[$oauthRequestTransfer->getGrantType()];
            return $this->grantTypeExecutor->processAccessTokenRequest(
                $oauthRequestTransfer,
                $grantTypeProviderPlugin->getGrantType()
            );
        }

        return $this->createErrorResponseTransfer($oauthRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    protected function createErrorResponseTransfer(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthResponseTransfer = new OauthResponseTransfer();
        $oauthErrorTransfer = new OauthErrorTransfer();
        $oauthErrorTransfer->setMessage(sprintf('Grant type "%s" not found', $oauthRequestTransfer->getGrantType()));
        $oauthResponseTransfer->setError($oauthErrorTransfer);

        return $oauthResponseTransfer;
    }

    /**
     * @param \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthGrantTypeProviderPluginInterface[] $grantTypeProviderPlugins
     *
     * @return void
     */
    protected function setGrantTypeProviderPlugins(array $grantTypeProviderPlugins): void
    {
        foreach ($grantTypeProviderPlugins as $grantTypeProviderPlugin) {
            $this->grantTypeProviderPlugins[$grantTypeProviderPlugin->getGrantTypeName()] = $grantTypeProviderPlugin;
        }
    }
}
