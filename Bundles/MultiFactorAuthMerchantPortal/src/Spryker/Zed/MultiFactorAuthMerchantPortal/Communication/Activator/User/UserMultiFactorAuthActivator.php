<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Activator\User;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConstants;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReaderInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class UserMultiFactorAuthActivator implements UserMultiFactorAuthActivatorInterface
{
    /**
     * @param \Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReaderInterface $requestReader
     */
    public function __construct(
        protected MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected RequestReaderInterface $requestReader
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function activate(Request $request, UserTransfer $userTransfer): void
    {
        $isActivation = $this->requestReader->get($request, MerchantUserController::IS_ACTIVATION);

        $status = $isActivation ? MultiFactorAuthMerchantPortalConstants::STATUS_PENDING_ACTIVATION : MultiFactorAuthMerchantPortalConstants::STATUS_ACTIVE;
        $type = $isActivation ? $this->requestReader->get($request, MerchantUserController::TYPE_TO_SET_UP) : $request->query->get(MultiFactorAuthTransfer::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setStatus($status)
            ->setType($type);

        $this->multiFactorAuthFacade->activateUserMultiFactorAuth($multiFactorAuthTransfer);
    }
}
