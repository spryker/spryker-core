<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class UserManagementMerchantPortalController extends UserManagementController
{
    /**
     * @var string
     */
    protected const URL_REDIRECT_SET_UP_PAGE = '/multi-factor-auth/user-management-merchant-portal/set-up';

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Controller\LoginController::indexAction()
     *
     * @var string
     */
    protected const LOGIN_PATH = '/security-merchant-portal-gui/login';

    /**
     * @return string
     */
    protected function getSetUpTemplatePath(): string
    {
        return '@MultiFactorAuth/UserManagement/set-up-merchant-portal.twig';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $multiFactorAuthType
     * @param string $csrfTokenId
     * @param string $formName
     *
     * @return bool
     */
    protected function isRequestInvalid(
        Request $request,
        UserTransfer $userTransfer,
        string $multiFactorAuthType,
        string $csrfTokenId,
        string $formName
    ): bool {
        return !$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), $csrfTokenId)
            || $this->isCodeBlocked($userTransfer, $multiFactorAuthType);
    }
}
