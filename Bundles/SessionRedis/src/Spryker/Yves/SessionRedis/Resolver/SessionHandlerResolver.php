<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Resolver;

use Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer;
use SessionHandlerInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface;
use Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionHandlerResolver implements SessionHandlerResolverInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Spryker\Shared\SessionRedis\Handler\SessionHandlerFactoryInterface $sessionHandlerFactory
     * @param array<\Spryker\Yves\SessionRedisExtension\Dependency\Plugin\SessionRedisLockingExclusionConditionPluginInterface> $sessionRedisLockingExclusionConditionPlugins
     */
    public function __construct(
        protected RequestStack $requestStack,
        protected SessionHandlerFactoryInterface $sessionHandlerFactory,
        protected array $sessionRedisLockingExclusionConditionPlugins = []
    ) {
    }

    /**
     * @param \Spryker\Shared\SessionRedis\Redis\SessionRedisWrapperInterface $sessionRedisWrapper
     *
     * @return \SessionHandlerInterface
     */
    public function resolveConfigurableRedisLockingSessionHandler(
        SessionRedisWrapperInterface $sessionRedisWrapper
    ): SessionHandlerInterface {
        $redisLockingSessionHandlerConditionTransfer = $this->createRedisLockingSessionHandlerConditionTransfer();

        foreach ($this->sessionRedisLockingExclusionConditionPlugins as $sessionRedisLockingExclusionConditionPlugin) {
            if ($sessionRedisLockingExclusionConditionPlugin->checkCondition($redisLockingSessionHandlerConditionTransfer) === true) {
                return $this->sessionHandlerFactory->createSessionRedisHandler($sessionRedisWrapper);
            }
        }

        return $this->sessionHandlerFactory->createSessionHandlerRedisLocking($sessionRedisWrapper);
    }

    /**
     * @return \Generated\Shared\Transfer\RedisLockingSessionHandlerConditionTransfer
     */
    protected function createRedisLockingSessionHandlerConditionTransfer(): RedisLockingSessionHandlerConditionTransfer
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request === null) {
            return (new RedisLockingSessionHandlerConditionTransfer());
        }

        return (new RedisLockingSessionHandlerConditionTransfer())
            ->setRequestUri($request->getRequestUri())
            ->setRequestMethod($request->getMethod())
            ->setRequestHeaders($request->headers->all());
    }
}
