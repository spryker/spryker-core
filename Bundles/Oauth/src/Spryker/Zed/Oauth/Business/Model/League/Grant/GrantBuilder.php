<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use Generated\Shared\Transfer\OauthGrantConfigurationTransfer;
use Spryker\Zed\Oauth\Business\Exception\InvalidGrantException;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;
use Spryker\Zed\Oauth\OauthConfig;

class GrantBuilder implements GrantBuilderInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface
     */
    protected $repositoryBuilder;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        RepositoryBuilderInterface $repositoryBuilder,
        OauthConfig $oauthConfig
    ) {
        $this->repositoryBuilder = $repositoryBuilder;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthGrantConfigurationTransfer $oauthGrantConfigurationTransfer
     *
     * @throws \Spryker\Zed\Oauth\Business\Exception\InvalidGrantException
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantInterface
     */
    public function buildGrant(OauthGrantConfigurationTransfer $oauthGrantConfigurationTransfer): GrantInterface
    {
        $fullyQualifiedClassName = $oauthGrantConfigurationTransfer->getFullyQualifiedClassName();
        $grant = new $fullyQualifiedClassName();

        if (!($grant instanceof GrantInterface)) {
            throw new InvalidGrantException(sprintf(
                'Provided grant must implement %s in order to use %s.',
                $fullyQualifiedClassName,
                static::class
            ));
        }

        $grant->setUserRepository($this->repositoryBuilder->createUserRepository());
        $grant->setRefreshTokenRepository($this->repositoryBuilder->createRefreshTokenRepository());
        $grant->setRefreshTokenTTL(new DateInterval($this->oauthConfig->getRefreshTokenTTL()));

        return $grant;
    }
}
