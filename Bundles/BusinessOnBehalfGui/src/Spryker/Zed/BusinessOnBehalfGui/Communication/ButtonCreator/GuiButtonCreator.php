<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;

class GuiButtonCreator implements GuiButtonCreatorInterface
{
    protected const BUTTON_DEFAULT_DELETE_COMPANY_USER_LINK = '/company-user-gui/delete-company-user/confirm-delete?id-company-user=';
    protected const BUTTON_DELETE_COMPANY_USER_URL = '<a href="/business-on-behalf-gui/delete-company-user/confirm-delete?id-company-user=%s" class="safe-submit btn btn-xs btn-outline btn-danger"><i class="fa fa-trash"></i> Delete</a>';
    protected const BUTTON_ATTACH_TO_BUSINESS_UNIT_URL = '<a href="/business-on-behalf-gui/create-company-user/attach-customer?id-customer=%s&id-company=%s" class="safe-submit btn btn-xs btn-outline btn-view"><i class="fa fa-paperclip"></i> Attach to BU</a>';

    /**
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function addDeleteButtonForCompanyUserTable(array $companyUserDataItem, array $actionButtons): array
    {
        foreach ($actionButtons as $key => $actionButton) {
            if (strripos($actionButton, static::BUTTON_DEFAULT_DELETE_COMPANY_USER_LINK)) {
                unset($actionButtons[$key]);
            }
        }
        $actionButtons[] = sprintf(
            static::BUTTON_DELETE_COMPANY_USER_URL,
            $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER]
        );

        return $actionButtons;
    }

    /**
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function addAttachToBusinessUnitButtonForCompanyUserTable(array $companyUserDataItem, array $actionButtons): array
    {
        $actionButtons[] = sprintf(
            static::BUTTON_ATTACH_TO_BUSINESS_UNIT_URL,
            $companyUserDataItem[SpyCompanyUserTableMap::COL_FK_CUSTOMER],
            $companyUserDataItem[SpyCompanyUserTableMap::COL_FK_COMPANY]
        );

        return $actionButtons;
    }
}
