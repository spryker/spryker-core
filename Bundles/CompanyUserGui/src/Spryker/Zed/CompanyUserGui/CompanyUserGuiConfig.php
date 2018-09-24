<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserGuiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\EditCompanyUserController::indexAction()
     */
    public const URL_EDIT_COMPANY_USER = '/company-user-gui/edit-company-user';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\CompanyUserStatusController::enableCompanyUserAction()
     */
    public const URL_ENABLE_COMPANY_USER = '/company-user-gui/company-user-status/enable-company-user';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\CompanyUserStatusController::disableCompanyUserAction()
     */
    public const URL_DISABLE_COMPANY_USER = '/company-user-gui/company-user-status/disable-company-user';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\DeleteCompanyUserController::deleteAction()
     */
    public const URL_DELETE_COMPANY_USER = '/company-user-gui/delete-company-user/delete';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\ListCompanyUserController::indexAction()
     */
    public const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';
}
