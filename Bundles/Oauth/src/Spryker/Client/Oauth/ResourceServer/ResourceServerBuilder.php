<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Spryker\Client\Oauth\OauthConfig;

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
     * @return \League\OAuth2\Server\ResourceServer
     */
    public function create(): ResourceServer
    {
        return new ResourceServer(
            $this->accessTokenRepository,
            $this->oauthConfig->getPublicKeyPath()
        );
    }
}
