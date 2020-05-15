<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Spryker\Client\Oauth\OauthConfig;
use Spryker\Client\OauthCryptography\ResourceServer\KeyLoader;
use Spryker\Client\OauthCryptography\ResourceServer\ResourceServer;

class ResourceServerBuilder implements ResourceServerBuilderInterface
{
    /**
     * @var \Spryker\Client\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @param \Spryker\Client\Oauth\OauthConfig $oauthConfig
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     */
    public function __construct(
        OauthConfig $oauthConfig,
        AccessTokenRepositoryInterface $accessTokenRepository
    ) {
        $this->oauthConfig = $oauthConfig;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @return \Spryker\Client\OauthCryptography\ResourceServer\ResourceServer
     */
    public function create(): ResourceServer
    {
        // Todo: move the creation to a factory. Inject the validators via DP.
        return new ResourceServer(
            new KeyLoader($this->oauthConfig),
            [new BearerTokenValidator($this->accessTokenRepository)]
        );
    }
}
