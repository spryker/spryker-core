<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Business\Resolver;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecurityOauthUser\Business\Exception\AuthenticationStrategyNotFoundException;
use Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;

class AuthenticationStrategyResolver implements AuthenticationStrategyResolverInterface
{
    /**
     * @var \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig
     */
    protected $securityOauthUserConfig;

    /**
     * @var \Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface[]
     */
    protected $authenticationStrategies;

    /**
     * @param \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig $securityOauthUserConfig
     * @param \Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface[] $authenticationStrategies
     */
    public function __construct(
        SecurityOauthUserConfig $securityOauthUserConfig,
        array $authenticationStrategies
    ) {
        $this->securityOauthUserConfig = $securityOauthUserConfig;
        $this->authenticationStrategies = $authenticationStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function resolveOauthUser(UserCriteriaTransfer $userCriteriaTransfer): ?UserTransfer
    {
        $oauthUserAuthenticationStrategy = $this->resolveOauthUserAuthenticationStrategy();

        return $oauthUserAuthenticationStrategy->resolveOauthUser($userCriteriaTransfer);
    }

    /**
     * @throws \Spryker\Zed\SecurityOauthUser\Business\Exception\AuthenticationStrategyNotFoundException
     *
     * @return \Spryker\Zed\SecurityOauthUser\Business\Strategy\AuthenticationStrategyInterface
     */
    protected function resolveOauthUserAuthenticationStrategy(): AuthenticationStrategyInterface
    {
        $authenticationStrategyName = $this->securityOauthUserConfig->getAuthenticationStrategy();

        foreach ($this->authenticationStrategies as $authenticationStrategy) {
            if ($authenticationStrategy->getAuthenticationStrategy() === $authenticationStrategyName) {
                return $authenticationStrategy;
            }
        }

        throw new AuthenticationStrategyNotFoundException(sprintf(
            'Authentication strategy with name "%s" not found',
            $authenticationStrategyName
        ));
    }
}
