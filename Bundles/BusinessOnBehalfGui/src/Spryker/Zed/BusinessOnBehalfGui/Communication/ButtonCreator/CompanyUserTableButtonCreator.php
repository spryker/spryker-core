<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;

class CompanyUserTableButtonCreator extends AbstractButtonCreator implements CompanyUserTableButtonCreatorInterface
{
    /**
     * @uses \Spryker\Zed\BusinessOnBehalfGui\Communication\Controller\CustomerController::attachCustomerAction
     */
    protected const PATH_ATTACH_CUSTOMER_TO_BUSINESS_UNIT = '/business-on-behalf-gui/customer/attach-customer';

    protected const PARAM_ID_CUSTOMER = 'id-customer';
    protected const PARAM_ID_COMPANY = 'id-company';

    protected const COL_FK_CUSTOMER = 'spy_company_user.fk_customer';
    protected const COL_FK_COMPANY = 'spy_company_user.fk_company';

    protected const BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE = 'Attach to BU';

    /**
     * @param array $companyUserTableRowItem
     * @param string[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function addAttachCustomerToBusinessUnitButton(array $companyUserTableRowItem, array $buttons): ButtonTransfer
    {
        $url = $this->generateUrl(static::PATH_ATTACH_CUSTOMER_TO_BUSINESS_UNIT, [
            static::PARAM_ID_CUSTOMER => $companyUserTableRowItem[static::COL_FK_CUSTOMER],
            static::PARAM_ID_COMPANY => $companyUserTableRowItem[static::COL_FK_COMPANY],
        ]);

        $defaultOptions = [
            'class' => 'safe-submit btn-view',
            'icon' => 'fa-paperclip',
        ];

        return $this->generateButtonTransfer(
            $url,
            static::BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE,
            $defaultOptions
        );
    }
}
