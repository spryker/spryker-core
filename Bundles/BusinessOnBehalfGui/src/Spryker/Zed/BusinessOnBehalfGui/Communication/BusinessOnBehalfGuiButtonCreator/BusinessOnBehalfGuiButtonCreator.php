<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\BusinessOnBehalfGuiButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Service\UtilText\Model\Url\Url;

class BusinessOnBehalfGuiButtonCreator implements BusinessOnBehalfGuiButtonCreatorInterface
{
    protected const BUTTON_DEFAULT_DELETE_COMPANY_USER_LINK = 'company-user-gui/delete-company-user/confirm-delete?id-company-user=';

    protected const URL_CONFIRM_DELETE_COMPANY_USER = '/business-on-behalf-gui/delete-company-user/confirm-delete';
    protected const URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT = '/business-on-behalf-gui/create-company-user/attach-customer';

    protected const PARAM_ID_COMPANY_USER = 'id-company-user';
    protected const PARAM_ID_CUSTOMER = 'id-customer';
    protected const PARAM_ID_COMPANY = 'id-company';

    protected const BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE = 'Attach to BU';

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function addNewDeleteCompanyUserButton(array $companyUserDataItem, array $buttonTransfers): array
    {
        foreach ($buttonTransfers as $key => $buttonTransfer) {
            if (strripos($buttonTransfer->getUrl(), static::BUTTON_DEFAULT_DELETE_COMPANY_USER_LINK)) {
                $url = $this->generateUrl(static::URL_CONFIRM_DELETE_COMPANY_USER, [
                    static::PARAM_ID_COMPANY_USER => $companyUserDataItem[SpyCompanyUserTableMap::COL_ID_COMPANY_USER],
                ]);

                $buttonTransfers[$key]->setUrl($url);
            }
        }

        return $buttonTransfers;
    }

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $actionButtons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function addAttachCustomerToBusinessUnitButton(array $companyUserDataItem, array $actionButtons): array
    {
        $url = $this->generateUrl(static::URL_ATTACH_CUSTOMER_TO_BUSINESS_UNIT, [
            static::PARAM_ID_CUSTOMER => $companyUserDataItem[SpyCompanyUserTableMap::COL_FK_CUSTOMER],
            static::PARAM_ID_COMPANY => $companyUserDataItem[SpyCompanyUserTableMap::COL_FK_COMPANY],
        ]);

        $defaultOptions = [
            'class' => 'safe-submit btn-view',
            'icon' => 'fa-paperclip',
        ];

        $actionButtons[] = $this->generateButtonTransfer($url, static::BUTTON_ATTACH_TO_BUSINESS_UNIT_TITLE, $defaultOptions);

        return $actionButtons;
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $defaultOptions
     * @param array|null $customOptions
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    protected function generateButtonTransfer(string $url, string $title, array $defaultOptions, ?array $customOptions = null): ButtonTransfer
    {
        return (new ButtonTransfer())
            ->setUrl($url)
            ->setTitle($title)
            ->setDefaultOptions($defaultOptions)
            ->setCustomOptions($customOptions);
    }

    /**
     * @param string $url
     * @param array $queryParams
     *
     * @return string
     */
    protected function generateUrl(string $url, array $queryParams): string
    {
        return Url::generate($url, $queryParams);
    }
}
