<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Deactivator\User;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Controller\UserController;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class UserMultiFactorAuthDeactivator implements UserMultiFactorAuthDeactivatorInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface $facade
     * @param \Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface $requestReader
     */
    public function __construct(
        protected MultiFactorAuthFacadeInterface $facade,
        protected RequestReaderInterface $requestReader
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function deactivate(Request $request, UserTransfer $userTransfer): void
    {
        $isDeactivation = $this->requestReader->get($request, UserController::IS_DEACTIVATION);

        $type = $isDeactivation ? $this->requestReader->get($request, UserController::TYPE_TO_SET_UP) : $request->query->get(MultiFactorAuthTransfer::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setStatus(MultiFactorAuthConstants::STATUS_INACTIVE)
            ->setType($type);

        $this->facade->deactivateUserMultiFactorAuth($multiFactorAuthTransfer);
    }
}
