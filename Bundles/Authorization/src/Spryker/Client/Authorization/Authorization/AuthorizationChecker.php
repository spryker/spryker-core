<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authorization\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Spryker\Client\Authorization\Exception\AuthorizationStrategyNotFoundException;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var \Spryker\Client\Authorization\Authorization\AuthorizationStrategyCollectionInterface
     */
    protected $authorizationStrategyCollection;

    /**
     * @param \Spryker\Client\Authorization\Authorization\AuthorizationStrategyCollectionInterface $authorizationStrategyCollection
     */
    public function __construct(AuthorizationStrategyCollectionInterface $authorizationStrategyCollection)
    {
        $this->authorizationStrategyCollection = $authorizationStrategyCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @throws \Spryker\Client\Authorization\Exception\AuthorizationStrategyNotFoundException
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

        foreach ($authorizationRequestTransfer->getStrategies() as $strategy) {
            if (!$this->authorizationStrategyCollection->has($strategy)) {
                throw new AuthorizationStrategyNotFoundException(sprintf('Authorization strategy `%s` not found.', $strategy));
            }
            $isAuthorized = $this->authorizationStrategyCollection
                ->get($strategy)
                ->authorize($authorizationRequestTransfer);

            $authorizationResponseTransfer
                ->setIsAuthorized($isAuthorized);

            if (!$authorizationResponseTransfer->getIsAuthorized()) {
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
