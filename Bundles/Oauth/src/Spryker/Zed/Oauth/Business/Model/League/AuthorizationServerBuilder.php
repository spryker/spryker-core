<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\AuthorizationServer as OauthServer;
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
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     */
    public function __construct(OauthConfig $oauthConfig, RepositoryBuilderInterface $repositoryBuilder)
    {
        $this->oauthConfig = $oauthConfig;
        $this->repositoryBuilder = $repositoryBuilder;
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
            $this->oauthConfig->getEncryptionKey()
        );
    }
}
