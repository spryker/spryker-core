<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Controller;

use Spryker\Zed\CompanyUserGui\CompanyUserGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractCompanyUserController extends AbstractController
{
    protected const PARAMETER_ID_COMPANY_USER = 'id-company-user';

    /**
     * @param string $errorMessage
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToCompanyUserListWithErrorMessage(string $errorMessage): RedirectResponse
    {
        $this->addErrorMessage($errorMessage);

        return $this->redirectResponse(CompanyUserGuiConfig::URL_REDIRECT_COMPANY_USER_PAGE);
    }
}
