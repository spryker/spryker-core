<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\ResourceServer;
use Spryker\Zed\Oauth\OauthConfig;

class ResourceServerBuilder implements ResourceServerBuilderInterface
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
    public function __construct(
        OauthConfig $oauthConfig,
        RepositoryBuilderInterface $repositoryBuilder
    ) {
        $this->oauthConfig = $oauthConfig;
        $this->repositoryBuilder = $repositoryBuilder;
    }

    /**
     * @return \League\OAuth2\Server\ResourceServer
     */
    public function build(): ResourceServer
    {
        return new ResourceServer(
            $this->repositoryBuilder->createAccessTokenRepository(),
            $this->oauthConfig->getPublicKeyPath()
        );
    }
}
