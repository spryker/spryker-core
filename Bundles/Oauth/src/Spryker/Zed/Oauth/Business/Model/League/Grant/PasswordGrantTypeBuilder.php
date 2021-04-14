<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Grant;

use DateInterval;
use Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface as GrantGrantTypeInterface;
use Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface;

class PasswordGrantTypeBuilder implements GrantTypeBuilderInterface
{
    /**
     * @param \Spryker\Zed\Oauth\Business\Model\League\RepositoryBuilderInterface $repositoryBuilder
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Spryker\Zed\Oauth\Business\Model\League\Grant\GrantTypeInterface
     */
    public function buildGrant(
        RepositoryBuilderInterface $repositoryBuilder,
        DateInterval $refreshTokenTTL
    ): GrantGrantTypeInterface {
        $grant = new PasswordGrantType();

        $grant->setUserRepository($repositoryBuilder->createUserRepository());
        $grant->setRefreshTokenRepository($repositoryBuilder->createRefreshTokenRepository());
        $grant->setRefreshTokenTTL($refreshTokenTTL);

        return $grant;
    }
}
