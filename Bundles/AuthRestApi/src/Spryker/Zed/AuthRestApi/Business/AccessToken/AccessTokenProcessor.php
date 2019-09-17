<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business\AccessToken;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface;

class AccessTokenProcessor implements AccessTokenProcessorInterface
{
    /**
     * @var \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface
     */
    protected $oauthFacade;

    /**
     * @var \Spryker\Zed\AuthRestApiExtension\Dependency\Plugin\PostAuthPluginInterface[]
     */
    protected $postAuthPlugins;

    /**
     * @param \Spryker\Zed\AuthRestApi\Dependency\Facade\AuthRestApiToOauthFacadeInterface $oauthFacade
     * @param \Spryker\Zed\AuthRestApiExtension\Dependency\Plugin\PostAuthPluginInterface[] $postAuthPlugins
     */
    public function __construct(
        AuthRestApiToOauthFacadeInterface $oauthFacade,
        array $postAuthPlugins
    ) {
        $this->oauthFacade = $oauthFacade;
        $this->postAuthPlugins = $postAuthPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer
    {
        $oauthResponseTransfer = $this->oauthFacade->processAccessTokenRequest($oauthRequestTransfer);
        if (!$oauthResponseTransfer->getIsValid()) {
            return $oauthResponseTransfer;
        }

        $oauthResponseTransfer->setAnonymousCustomerReference($oauthRequestTransfer->getCustomerReference());

        foreach ($this->postAuthPlugins as $postAuthPlugin) {
            $postAuthPlugin->postAuth($oauthResponseTransfer);
        }

        return $oauthResponseTransfer;
    }
}
