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
        $authorizationResponseTransfer = new AuthorizationResponseTransfer();

        if (!$this->authorizationStrategyCollection->has((string)$authorizationRequestTransfer->getStrategy())) {
            throw new AuthorizationStrategyNotFoundException(sprintf('Authorization strategy `%s` not found.', $authorizationRequestTransfer->getStrategy()));
        }

        $result = $this->authorizationStrategyCollection
            ->get((string)$authorizationRequestTransfer->getStrategy())
            ->authorize($authorizationRequestTransfer);

        $authorizationResponseTransfer
            ->setIsAuthorized($result);

        return $authorizationResponseTransfer;
    }
}
