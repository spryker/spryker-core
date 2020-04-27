<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\AuthorizationServer as OauthServer;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Spryker\Zed\Oauth\OauthConfig;

class AuthorizationServerBuilder implements AuthorizationServerBuilderInterface
{
    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface
     */
    protected $repositoryBuilder;

    /**
     * @var \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface
     */
    protected $responseType;

    /**
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface $responseType
     */
    public function __construct(
        OauthConfig $oauthConfig,
        RepositoryBuilderInterface $repositoryBuilder,
        ResponseTypeInterface $responseType
    ) {
        $this->oauthConfig = $oauthConfig;
        $this->repositoryBuilder = $repositoryBuilder;
        $this->responseType = $responseType;
    }

    /**
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function build(): OauthServer
    {
        return new OauthServer(
            $this->repositoryBuilder->createClientRepository(),
            $this->repositoryBuilder->createAccessTokenRepository(),
            $this->repositoryBuilder->createScopeRepository(),
            $this->oauthConfig->getPrivateKeyPath(),
            $this->oauthConfig->getEncryptionKey(),
            $this->responseType
        );
    }
}
