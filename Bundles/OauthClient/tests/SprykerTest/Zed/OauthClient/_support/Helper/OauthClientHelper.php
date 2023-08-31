<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\OauthClient\Helper;

use Codeception\Stub;
use Generated\Shared\DataBuilder\AccessTokenRequestBuilder;
use Generated\Shared\DataBuilder\AccessTokenResponseBuilder;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCacheQuery;
use Spryker\Zed\OauthClient\OauthClientDependencyProvider;
use Spryker\Zed\OauthDummy\Communication\Plugin\OauthClient\DummyOauthAccessTokenProviderPlugin;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

class OauthClientHelper extends AbstractHelper
{
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer|null $accessTokenRequestTransfer
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer|null $accessTokenResponseTransfer
     *
     * @return \Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache
     */
    public function haveOauthClientAccessTokenCacheEntity(
        ?AccessTokenRequestTransfer $accessTokenRequestTransfer = null,
        ?AccessTokenResponseTransfer $accessTokenResponseTransfer = null
    ): SpyOauthClientAccessTokenCache {
        if (!$accessTokenRequestTransfer) {
            $accessTokenRequestTransfer = $this->haveAccessTokenRequestTransfer();
        }

        if (!$accessTokenResponseTransfer) {
            $accessTokenResponseTransfer = $this->haveAccessTokenResponseTransfer();
        }

        $spyOauthClientAccessTokenCacheEntity = SpyOauthClientAccessTokenCacheQuery::create()
            ->filterByCacheKey(sha1(serialize($accessTokenRequestTransfer->modifiedToArray())))
            ->findOneOrCreate();

        $spyOauthClientAccessTokenCacheEntity
            ->setAccessToken($accessTokenResponseTransfer->getAccessToken())
            ->setExpiresAt($accessTokenResponseTransfer->getExpiresAt());

        $spyOauthClientAccessTokenCacheEntity->save();

        $this->getDataCleanupHelper()->addCleanup(function () use ($spyOauthClientAccessTokenCacheEntity): void {
            $spyOauthClientAccessTokenCacheEntity->delete();
        });

        return $spyOauthClientAccessTokenCacheEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer $accessTokenResponseTransfer
     * @param bool $isApplicable
     *
     * @return void
     */
    public function haveDummyOauthAccessTokenProviderDependency(
        AccessTokenResponseTransfer $accessTokenResponseTransfer,
        bool $isApplicable
    ): void {
        $dummyOauthPlugin = Stub::make(DummyOauthAccessTokenProviderPlugin::class, [
            'isApplicable' => $isApplicable,
            'getAccessToken' => $accessTokenResponseTransfer,
        ]);

        $this->getDependencyHelper()->setDependency(
            OauthClientDependencyProvider::PLUGINS_OAUTH_ACCESS_TOKEN_PROVIDER,
            [$dummyOauthPlugin],
        );
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function haveAccessTokenRequestTransfer(array $seedData = []): AccessTokenRequestTransfer
    {
        return (new AccessTokenRequestBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function haveAccessTokenResponseTransfer(array $seedData = []): AccessTokenResponseTransfer
    {
        return (new AccessTokenResponseBuilder($seedData))->build();
    }
}
