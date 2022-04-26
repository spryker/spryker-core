<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Business\Provider;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Zed\OauthClient\Business\Cache\AccessTokenCacheInterface;
use Spryker\Zed\OauthClient\Business\Exception\AccessTokenProviderNotFoundException;
use Spryker\Zed\OauthClient\OauthClientConfig;

class OauthAccessTokenProvider implements OauthAccessTokenProviderInterface
{
    /**
     * @var array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface>
     */
    protected $oauthAccessTokenProviderPlugins;

    /**
     * @var \Spryker\Zed\OauthClient\Business\Cache\AccessTokenCacheInterface
     */
    protected $accessTokenCache;

    /**
     * @var \Spryker\Zed\OauthClient\OauthClientConfig
     */
    protected $oauthClientConfig;

    /**
     * @var array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface>
     */
    protected $accessTokenRequestExpanderPlugins;

    /**
     * @param array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface> $oauthAccessTokenProviderPlugins
     * @param \Spryker\Zed\OauthClient\Business\Cache\AccessTokenCacheInterface $accessTokenCache
     * @param \Spryker\Zed\OauthClient\OauthClientConfig $oauthClientConfig
     * @param array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface> $accessTokenRequestExpanderPlugins
     */
    public function __construct(
        array $oauthAccessTokenProviderPlugins,
        AccessTokenCacheInterface $accessTokenCache,
        OauthClientConfig $oauthClientConfig,
        array $accessTokenRequestExpanderPlugins
    ) {
        $this->oauthAccessTokenProviderPlugins = $oauthAccessTokenProviderPlugins;
        $this->accessTokenCache = $accessTokenCache;
        $this->oauthClientConfig = $oauthClientConfig;
        $this->accessTokenRequestExpanderPlugins = $accessTokenRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer
    {
        $isCacheEnabled = $this->isOauthResponseCacheEnabled($accessTokenRequestTransfer);

        $accessTokenRequestTransfer = $this->executeAccessTokenRequestExpanderPlugins($accessTokenRequestTransfer);

        if ($isCacheEnabled) {
            $accessTokenResponseTransfer = $this->accessTokenCache->get($accessTokenRequestTransfer);

            if ($accessTokenResponseTransfer->getIsSuccessful()) {
                return $accessTokenResponseTransfer;
            }
        }

        return $this->executeOauthAccessTokenProviderPlugins($accessTokenRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return bool
     */
    protected function isOauthResponseCacheEnabled(AccessTokenRequestTransfer $accessTokenRequestTransfer): bool
    {
        return $this->oauthClientConfig->isCacheEnabled()
            && $accessTokenRequestTransfer->getIgnoreCache() !== true;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @throws \Spryker\Zed\OauthClient\Business\Exception\AccessTokenProviderNotFoundException
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    protected function executeOauthAccessTokenProviderPlugins(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenResponseTransfer {
        $isCacheEnabled = $this->isOauthResponseCacheEnabled($accessTokenRequestTransfer);

        foreach ($this->oauthAccessTokenProviderPlugins as $oauthAccessTokenProviderPlugin) {
            if ($oauthAccessTokenProviderPlugin->isApplicable($accessTokenRequestTransfer)) {
                $accessTokenResponseTransfer = $oauthAccessTokenProviderPlugin
                    ->getAccessToken($accessTokenRequestTransfer);

                if ($isCacheEnabled && $accessTokenResponseTransfer->getIsSuccessful()) {
                    $this->accessTokenCache->set($accessTokenRequestTransfer, $accessTokenResponseTransfer);
                }

                return $accessTokenResponseTransfer;
            }
        }

        throw new AccessTokenProviderNotFoundException(sprintf(
            'Applicable access token provider "%s" is not found.',
            $accessTokenRequestTransfer->getProviderName(),
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    protected function executeAccessTokenRequestExpanderPlugins(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenRequestTransfer {
        foreach ($this->accessTokenRequestExpanderPlugins as $accessTokenRequestExpanderPlugin) {
            $accessTokenRequestTransfer = $accessTokenRequestExpanderPlugin->expand($accessTokenRequestTransfer);
        }

        return $accessTokenRequestTransfer;
    }
}
