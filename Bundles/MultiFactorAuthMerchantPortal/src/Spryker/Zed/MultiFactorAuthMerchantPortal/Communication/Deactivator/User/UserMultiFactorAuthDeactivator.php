<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Deactivator\User;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConstants;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Reader\Request\RequestReaderInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Dependency\Facade\MultiFactorAuthMerchantPortalToMultiFactorAuthFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

class UserMultiFactorAuthDeactivator implements UserMultiFactorAuthDeactivatorInterface
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
    public function deactivate(Request $request, UserTransfer $userTransfer): void
    {
        $isDeactivation = $this->requestReader->get($request, MerchantUserController::IS_DEACTIVATION);

        $type = $isDeactivation ? $this->requestReader->get($request, MerchantUserController::TYPE_TO_SET_UP) : $request->query->get(MultiFactorAuthTransfer::TYPE);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setStatus(MultiFactorAuthMerchantPortalConstants::STATUS_INACTIVE)
            ->setType($type);

        $this->multiFactorAuthFacade->deactivateUserMultiFactorAuth($multiFactorAuthTransfer);
    }
}
