<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Shared\CompanySupplier\CompanySupplierConstants;
use Spryker\Zed\CompanyGui\Communication\Table\CompanyTable;
use Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableActionExpanderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class CompanyTableActionViewSupplier extends AbstractPlugin implements CompanyTableActionExpanderInterface
{
    protected const FK_COMPANY_TYPE = SpyCompanyTableMap::COL_FK_COMPANY_TYPE;
    protected const BUTTON_URL_FORMAT = '/company-supplier-gui/product-supplier?id-company=%d';
    protected const BUTTON_TITLE = 'View items';
    protected const BUTTON_DEFAULT_OPTIONS = [
        'class' => 'btn-view',
        'icon' => 'fa-caret-right',
    ];

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $company
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function prepareButton(array $company): ButtonTransfer
    {
        $button = new ButtonTransfer();
        if ($this->getCompanyType($company) === CompanySupplierConstants::COMPANY_SUPPLIER_TYPE) {
            $button->setUrl(sprintf(static::BUTTON_URL_FORMAT, $company[CompanyTable::COL_ID_COMPANY]));
            $button->setTitle(static::BUTTON_TITLE);
            $button->setDefaultOptions(
                static::BUTTON_DEFAULT_OPTIONS
            );
        }

        return $button;
    }

    /**
     * @param array $company
     *
     * @return string
     */
    protected function getCompanyType(array $company): string
    {
        if (!$company[static::FK_COMPANY_TYPE]) {
            return '';
        }
        $companyTypeTransfer = $this->getFactory()
            ->getCompanySupplierFacade()
            ->getCompanyTypeByIdCompanyType($company[static::FK_COMPANY_TYPE]);

        return $companyTypeTransfer->getName();
    }
}
