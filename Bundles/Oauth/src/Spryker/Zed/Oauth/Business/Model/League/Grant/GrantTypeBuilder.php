<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer;
use Spryker\Zed\Oauth\Business\Exception\InvalidBuilderException;
use Spryker\Zed\Oauth\Business\Exception\InvalidGrantException;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;
use Spryker\Zed\Oauth\OauthConfig;

class GrantTypeBuilder implements GrantBuilderInterface
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
     * @param \Generated\Shared\Transfer\OauthGrantTypeConfigurationTransfer $oauthGrantTypeConfigurationTransfer
     *
     * @throws \Spryker\Zed\Oauth\Business\Exception\InvalidGrantException
     * @throws \Spryker\Zed\Oauth\Business\Exception\InvalidBuilderException
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface
     */
    public function buildGrant(OauthGrantTypeConfigurationTransfer $oauthGrantTypeConfigurationTransfer): GrantTypeInterface
    {
        $builderFullyQualifiedClassName = $oauthGrantTypeConfigurationTransfer->getBuilderFullyQualifiedClassName();

        if ($builderFullyQualifiedClassName) {
            if (!class_exists($builderFullyQualifiedClassName) || !is_subclass_of($builderFullyQualifiedClassName, GrantTypeBuilderInterface::class)) {
                throw new InvalidBuilderException(sprintf(
                    'Provided builder %s must implement %s',
                    $builderFullyQualifiedClassName,
                    GrantTypeBuilderInterface::class
                ));
            }

            /** @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeBuilderInterface $grantBuilder */
            $grantBuilder = new $builderFullyQualifiedClassName();

            return $grantBuilder->buildGrant($this->repositoryBuilder, new DateInterval($this->oauthConfig->getRefreshTokenTTL()));
        }

        $fullyQualifiedClassName = $oauthGrantTypeConfigurationTransfer->getFullyQualifiedClassName();
        if (!$fullyQualifiedClassName) {
            throw new InvalidGrantException(sprintf(
                'Grant implementing %s is required in order to use %s.',
                GrantTypeInterface::class,
                static::class
            ));
        }
        if (!class_exists($fullyQualifiedClassName) || !is_subclass_of($fullyQualifiedClassName, GrantTypeInterface::class)) {
            throw new InvalidGrantException(sprintf(
                'Provided grant %s must implement %s in order to use %s.',
                $fullyQualifiedClassName,
                GrantTypeInterface::class,
                static::class
            ));
        }

        /** @var \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface $grant */
        $grant = new $fullyQualifiedClassName();
        $grant->setUserRepository($this->repositoryBuilder->createUserRepository());
        $grant->setRefreshTokenRepository($this->repositoryBuilder->createRefreshTokenRepository());
        $grant->setRefreshTokenTTL(new DateInterval($this->oauthConfig->getRefreshTokenTTL()));

        return $grant;
    }
}
