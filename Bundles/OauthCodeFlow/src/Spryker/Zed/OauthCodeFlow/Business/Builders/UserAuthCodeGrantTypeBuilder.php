<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow\Business\Builders;

use DateInterval;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\EntityManagerResolverAwareTrait;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeBuilderInterface;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;
use Spryker\Zed\OauthCodeFlow\Business\Grant\AuthCodeGrantType;
use Spryker\Zed\OauthCodeFlow\Business\Repositories\AuthCodeRepository;

/**
 * @method \Spryker\Zed\OauthCodeFlow\OauthCodeFlowConfig getConfig()
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowRepositoryInterface getRepository()
 * @method \Spryker\Zed\OauthCodeFlow\Persistence\OauthCodeFlowEntityManagerInterface getEntityManager()
 */
class UserAuthCodeGrantTypeBuilder implements GrantTypeBuilderInterface
{
    /**
     * @todo Should be refactored before module stabilization.
     */
    use BundleConfigResolverAwareTrait;
    use RepositoryResolverAwareTrait;
    use EntityManagerResolverAwareTrait;

    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface
     */
    public function buildGrant(
        RepositoryBuilderInterface $repositoryBuilder,
        DateInterval $refreshTokenTTL
    ): GrantTypeInterface {
        $userAuthCodeGrantType = new AuthCodeGrantType(
            $this->getConfig(),
            $this->createAuthCodeRepository(),
            $repositoryBuilder->createRefreshTokenRepository(),
        );

        return $userAuthCodeGrantType;
    }

    /**
     * @return \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface
     */
    protected function createAuthCodeRepository(): AuthCodeRepositoryInterface
    {
        return new AuthCodeRepository(
            $this->getRepository(),
            $this->getEntityManager(),
        );
    }
}
