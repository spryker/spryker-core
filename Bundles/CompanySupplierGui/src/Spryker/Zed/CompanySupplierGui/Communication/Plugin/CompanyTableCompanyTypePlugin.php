<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableConfigExpanderPluginInterface;
use Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableDataExpanderPluginInterface;
use Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableHeaderExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class CompanyTableCompanyTypePlugin extends AbstractPlugin implements CompanyTableConfigExpanderPluginInterface, CompanyTableHeaderExpanderPluginInterface, CompanyTableDataExpanderPluginInterface
{
    protected const COL_COMPANY_TYPE = 'company_type';
    protected const COL_COMPANY_TYPE_LABEL = 'Company Type';
    protected const FK_COMPANY_TYPE = SpyCompanyTableMap::COL_FK_COMPANY_TYPE;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array
    {
        return [static::COL_COMPANY_TYPE => static::COL_COMPANY_TYPE_LABEL];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $config;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [static::COL_COMPANY_TYPE => $this->getCompanyType($item)];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getCompanyType(array $item): string
    {
        if (!$item[static::FK_COMPANY_TYPE]) {
            return '';
        }

        $companyTypeTransfer = $this->getFactory()
            ->getCompanySupplierFacade()
            ->getCompanyTypeByIdCompanyType($item[static::FK_COMPANY_TYPE]);

        return $companyTypeTransfer->getName();
    }
}
