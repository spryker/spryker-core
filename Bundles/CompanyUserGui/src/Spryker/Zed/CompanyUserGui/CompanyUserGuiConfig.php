<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CompanyUserGuiConfig extends AbstractBundleConfig
{
    public const COL_ID_COMPANY_USER = 'id_company_user';

    public const PARAM_ID_COMPANY_USER = 'id-company-user';
    public const PARAM_ID_CUSTOMER = 'id-customer';
    public const PARAM_REDIRECT_URL = 'redirect-url';

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
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\DeleteCompanyUserController::confirmDeleteAction()
     */
    public const URL_CONFIRM_DELETE_COMPANY_USER = '/company-user-gui/delete-company-user/confirm-delete';

    /**
     * @see \Spryker\Zed\CompanyUserGui\Communication\Controller\ListCompanyUserController::indexAction()
     */
    public const URL_REDIRECT_COMPANY_USER_PAGE = '/company-user-gui/list-company-user';
}
