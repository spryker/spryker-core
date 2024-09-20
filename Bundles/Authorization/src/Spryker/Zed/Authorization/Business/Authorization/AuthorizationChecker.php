<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization\Business\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\DetachedAuthorizationStrategyPluginInterface;
use Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyNotFoundException;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_STRATEGY_NOT_FOUND = 'Authorization strategy `%s` not found.';

    /**
     * @var \Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollectionInterface
     */
    protected AuthorizationStrategyCollectionInterface $authorizationStrategyCollection;

    /**
     * @param \Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollectionInterface $authorizationStrategyCollection
     */
    public function __construct(AuthorizationStrategyCollectionInterface $authorizationStrategyCollection)
    {
        $this->authorizationStrategyCollection = $authorizationStrategyCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @throws \Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): AuthorizationResponseTransfer
    {
        $authorizationRequestTransfer = $this->addDefaultStrategy($authorizationRequestTransfer);

        $authorizationResponseTransfer = new AuthorizationResponseTransfer();

        if (!$authorizationRequestTransfer->getStrategies()) {
            return $authorizationResponseTransfer->setIsAuthorized(true);
        }

        $detachedStrategies = [];
        $strategies = [];
        foreach ($authorizationRequestTransfer->getStrategies() as $strategy) {
            if (!$this->authorizationStrategyCollection->has($strategy)) {
                throw new AuthorizationStrategyNotFoundException(sprintf(
                    static::MESSAGE_STRATEGY_NOT_FOUND,
                    $strategy,
                ));
            }

            $authorizationStrategy = $this->authorizationStrategyCollection->get($strategy);

            if ($authorizationStrategy instanceof DetachedAuthorizationStrategyPluginInterface) {
                $detachedStrategies[$strategy] = $authorizationStrategy;

                continue;
            }

            $strategies[$strategy] = $authorizationStrategy;
        }

        $authorizationResponseTransfer = $this->authorizeDetachedStrategies($detachedStrategies, $authorizationRequestTransfer, $authorizationResponseTransfer);
        if ($authorizationResponseTransfer->getIsAuthorized() === true) {
            return $authorizationResponseTransfer;
        }

        return $this->authorizeStrategies($strategies, $authorizationRequestTransfer, $authorizationResponseTransfer);
    }

    /**
     * @param array<\Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface> $strategies
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\AuthorizationResponseTransfer $authorizationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    protected function authorizeDetachedStrategies(
        array $strategies,
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        AuthorizationResponseTransfer $authorizationResponseTransfer
    ): AuthorizationResponseTransfer {
        foreach ($strategies as $strategy => $authorizationStrategy) {
            $isAuthorized = $authorizationStrategy->authorize($authorizationRequestTransfer);

            if ($isAuthorized === true) {
                return $authorizationResponseTransfer->setIsAuthorized(true);
            }

            $authorizationResponseTransfer->setFailedStrategy($strategy);
        }

        return $authorizationResponseTransfer;
    }

    /**
     * @param array<\Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface> $strategies
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param \Generated\Shared\Transfer\AuthorizationResponseTransfer $authorizationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    protected function authorizeStrategies(
        array $strategies,
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        AuthorizationResponseTransfer $authorizationResponseTransfer
    ): AuthorizationResponseTransfer {
        if ($strategies !== []) {
            $authorizationResponseTransfer
                ->setFailedStrategy(null)
                ->setIsAuthorized(false);
        }

        foreach ($strategies as $strategy => $authorizationStrategy) {
            $isAuthorized = $authorizationStrategy->authorize($authorizationRequestTransfer);
            $authorizationResponseTransfer->setIsAuthorized($isAuthorized);

            if ($isAuthorized === false) {
                $authorizationResponseTransfer->setFailedStrategy($strategy);

                return $authorizationResponseTransfer;
            }
        }

        return $authorizationResponseTransfer;
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    protected function addDefaultStrategy(AuthorizationRequestTransfer $authorizationRequestTransfer): AuthorizationRequestTransfer
    {
        if ($authorizationRequestTransfer->getStrategy()) {
            $authorizationRequestTransfer->addStrategy($authorizationRequestTransfer->getStrategy());
        }

        return $authorizationRequestTransfer;
    }
}
