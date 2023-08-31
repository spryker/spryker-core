<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OauthClient;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\MessageAttributesBuilder;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCacheQuery;
use Spryker\Zed\OauthClient\OauthClientDependencyProvider;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\SprykerTest\Zed\OauthClient\PHPMD)
 *
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 */
class OauthClientBusinessTester extends Actor
{
    use _generated\OauthClientBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return string
     */
    public function hashAccessTokenRequestTransfer(AccessTokenRequestTransfer $accessTokenRequestTransfer): string
    {
        return sha1(serialize($accessTokenRequestTransfer->modifiedToArray()));
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function removeCacheEntity(string $cacheKey): void
    {
        SpyOauthClientAccessTokenCacheQuery::create()->filterByCacheKey($cacheKey)->delete();
    }

    /**
     * @param array<\Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface> $pluginStack
     *
     * @return void
     */
    public function setOauthAccessTokenProviderPluginsDependency(array $pluginStack): void
    {
        $this->setDependency(
            OauthClientDependencyProvider::PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER,
            $pluginStack,
        );
    }

    /**
     * @param string $providerName
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer $expectedAccessTokenResponseTransfer
     *
     * @return \Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface
     */
    public function mockOauthAccessTokenProviderPlugin(
        string $providerName,
        AccessTokenResponseTransfer $expectedAccessTokenResponseTransfer
    ): OauthAccessTokenProviderPluginInterface {
        /** @var \Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface $oauthAccessTokenProviderPluginMock */
        $oauthAccessTokenProviderPluginMock = Stub::makeEmpty(OauthAccessTokenProviderPluginInterface::class, [
            'isApplicable' => function (AccessTokenRequestTransfer $accessTokenRequestTransfer) use ($providerName) {
                return $accessTokenRequestTransfer->getProviderName() === $providerName;
            },
            'getAccessToken' => $expectedAccessTokenResponseTransfer,
        ]);

        return $oauthAccessTokenProviderPluginMock;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function haveMessageAttributes(array $seedData = []): MessageAttributesTransfer
    {
        return (new MessageAttributesBuilder($seedData))->build();
    }
}
