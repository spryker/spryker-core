<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Activator\User;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Controller\UserController;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class UserMultiFactorAuthActivator implements UserMultiFactorAuthActivatorInterface
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
     * @param \Generated\Shared\Transfer\UserTransfer $UserTransfer
     *
     * @return void
     */
    public function activate(Request $request, UserTransfer $UserTransfer): void
    {
        $isActivation = $this->requestReader->get($request, UserController::IS_ACTIVATION);

        $status = $isActivation ? MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION : MultiFactorAuthConstants::STATUS_ACTIVE;
        $type = $isActivation ? $this->requestReader->get($request, UserController::TYPE_TO_SET_UP) : $request->query->get(MultiFactorAuthTransfer::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($UserTransfer)
            ->setStatus($status)
            ->setType($type);

        $this->facade->activateUserMultiFactorAuth($multiFactorAuthTransfer);
    }
}
