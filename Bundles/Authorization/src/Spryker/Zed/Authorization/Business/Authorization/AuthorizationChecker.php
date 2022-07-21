<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization\Business\Authorization;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyNotFoundException;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    /**
     * @var \Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollectionInterface
     */
    protected $authorizationStrategyCollection;

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
        if (!$this->authorizationStrategyCollection->has($authorizationRequestTransfer->getStrategyOrFail())) {
            throw new AuthorizationStrategyNotFoundException(sprintf(
                'Authorization strategy `%s` not found.',
                $authorizationRequestTransfer->getStrategy(),
            ));
        }

        $result = $this->authorizationStrategyCollection
            ->get($authorizationRequestTransfer->getStrategyOrFail())
            ->authorize($authorizationRequestTransfer);

        return (new AuthorizationResponseTransfer())->setIsAuthorized($result);
    }
}
