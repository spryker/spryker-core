<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

class CompanyUserTableButtonCreator extends AbstractButtonCreator implements CompanyUserTableButtonCreatorInterface
{
    protected const BUTTON_ORIGINAL_DELETE_COMPANY_USER_LINK = '/company-user-gui/delete-company-user/confirm-delete';

    protected const URL_CONFIRM_DELETE_COMPANY_USER = '/business-on-behalf-gui/delete-company-user/confirm-delete';
    protected const URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT = '/business-on-behalf-gui/customer/attach-customer';

    protected const PARAM_ID_COMPANY_USER = 'id-company-user';
    protected const PARAM_ID_CUSTOMER = 'id-customer';
    protected const PARAM_ID_COMPANY = 'id-company';

    protected const COL_ID_COMPANY_USER = 'spy_company_user.id_company_user';
    protected const COL_FK_CUSTOMER = 'spy_company_user.fk_customer';
    protected const COL_FK_COMPANY = 'spy_company_user.fk_company';

    protected const BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE = 'Attach to BU';

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function replaceDeleteCompanyUserButton(array $companyUserDataItem, array $buttonTransfers): array
    {
        foreach ($buttonTransfers as $key => $buttonTransfer) {
            $queryParams = [
                static::PARAM_ID_COMPANY_USER => $companyUserDataItem[static::COL_ID_COMPANY_USER],
            ];

            $oldDeleteUrl = $this->generateUrl(static::BUTTON_ORIGINAL_DELETE_COMPANY_USER_LINK, $queryParams);

            if ($buttonTransfer->getUrl() === $oldDeleteUrl) {
                $newDeleteUrl = $this->generateUrl(static::URL_CONFIRM_DELETE_COMPANY_USER, $queryParams);
                $buttonTransfers[$key]->setUrl($newDeleteUrl);
            }
        }

        return $buttonTransfers;
    }

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function addAttachCustomerToBusinessUnitButton(array $companyUserDataItem, array $buttonTransfers): array
    {
        $url = $this->generateUrl(static::URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT, [
            static::PARAM_ID_CUSTOMER => $companyUserDataItem[static::COL_FK_CUSTOMER],
            static::PARAM_ID_COMPANY => $companyUserDataItem[static::COL_FK_COMPANY],
        ]);

        $defaultOptions = [
            'class' => 'safe-submit btn-view',
            'icon' => 'fa-paperclip',
        ];

        $buttonTransfers[] = $this->generateButtonTransfer(
            $url,
            static::BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE,
            $defaultOptions
        );

        return $buttonTransfers;
    }
}
