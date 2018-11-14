<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListGuiExtension;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableHeaderExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableQueryExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipTableExpanderPlugin extends AbstractPlugin implements ProductListTableConfigExpanderPluginInterface, ProductListTableQueryExpanderPluginInterface, ProductListTableDataExpanderPluginInterface, ProductListTableHeaderExpanderPluginInterface
{
    public const COL_MERCHANT_NAME_ALIAS = 'spy_merchant_name';
    public const COL_BUSINESS_UNIT_OWNER_NAME_ALIAS = 'spy_company_business_unit_name';

    protected const HEADER_MERCHANT_RELATION_ID = 'ID Merchant Relation';
    protected const HEADER_MERCHANT_NAME = 'Merchant Name';
    protected const HEADER_BUSINESS_UNIT_OWNER_NAME = 'Business Unit Owner Name';

    protected const COL_FK_MERCHANT_RELATIONSHIP = SpyProductListTableMap::COL_FK_MERCHANT_RELATIONSHIP;
    protected const COL_MERCHANT_NAME = SpyMerchantTableMap::COL_NAME;
    protected const COL_COMPANY_BUSINESS_UNIT_NAME = SpyCompanyBusinessUnitTableMap::COL_NAME;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function expandHeader(): array
    {
        return [
            static::COL_FK_MERCHANT_RELATIONSHIP => static::HEADER_MERCHANT_RELATION_ID,
            static::COL_MERCHANT_NAME_ALIAS => static::HEADER_MERCHANT_NAME,
            static::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS => static::HEADER_BUSINESS_UNIT_OWNER_NAME,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandProductListQuery(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer
    {
        return $this
            ->getFactory()
            ->getProductListQueryExpander()
            ->buildProductListMerchantQueryCriteria($queryCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $sortable = array_merge($config->getSortable(), [
            static::COL_FK_MERCHANT_RELATIONSHIP,
            static::COL_MERCHANT_NAME_ALIAS,
            static::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS,
        ]);

        $config->setSortable($sortable);

        $searchable = array_merge($config->getSearchable(), [
            static::COL_FK_MERCHANT_RELATIONSHIP,
            static::COL_MERCHANT_NAME,
            static::COL_COMPANY_BUSINESS_UNIT_NAME,
        ]);

        $config->setSearchable($searchable);

        return $config;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [
            static::COL_FK_MERCHANT_RELATIONSHIP => $item[static::COL_FK_MERCHANT_RELATIONSHIP],
            static::COL_MERCHANT_NAME_ALIAS => $item[static::COL_MERCHANT_NAME_ALIAS],
            static::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS => $item[static::COL_BUSINESS_UNIT_OWNER_NAME_ALIAS],
        ];
    }
}
