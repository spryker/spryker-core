<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractCompanyUserController extends AbstractController
{
    protected const PARAMETER_ID_COMPANY_USER = 'id-company-user';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\ListCompanyUserController::indexAction()
     */
    protected const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToCompanyUserListWithErrorMessage(string $errorMessage): RedirectResponse
    {
        $this->addErrorMessage($errorMessage);

        return $this->redirectResponse(static::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
