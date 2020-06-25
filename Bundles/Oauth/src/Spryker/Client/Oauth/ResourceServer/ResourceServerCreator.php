<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoaderInterface;

class ResourceServerCreator implements ResourceServerCreatorInterface
{
    /**
     * @var \Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoaderInterface
     */
    protected $keyLoader;

    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    protected $accessTokenRepository;

    /**
     * @var \Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface[]
     */
    protected $authorizationValidatorPlugins;

    /**
     * @param \Spryker\Client\Oauth\ResourceServer\KeyLoader\KeyLoaderInterface $keyLoader
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     * @param \Spryker\Client\OauthExtension\Dependency\Plugin\AuthorizationValidatorPluginInterface[] $authorizationValidatorPlugins
     */
    public function __construct(
        KeyLoaderInterface $keyLoader,
        AccessTokenRepositoryInterface $accessTokenRepository,
        array $authorizationValidatorPlugins
    ) {
        $this->keyLoader = $keyLoader;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->authorizationValidatorPlugins = $authorizationValidatorPlugins;
    }

    /**
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServer
     */
    public function create(): ResourceServer
    {
        return new ResourceServer(
            $this->keyLoader->loadKeys(),
            $this->accessTokenRepository,
            $this->authorizationValidatorPlugins
        );
    }
}
