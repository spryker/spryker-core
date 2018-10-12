<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProductListGuiExtension;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantRelationship\Persistence\Map\SpyMerchantRelationshipTableMap;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableQueryExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableHeaderExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipTableExpanderPlugin extends AbstractPlugin implements ProductListTableConfigExpanderPluginInterface, ProductListTableQueryExpanderPluginInterface, ProductListTableDataExpanderPluginInterface, ProductListTableHeaderExpanderPluginInterface
{
    protected const COLUMN_MERCHANT_RELATION_ID = SpyMerchantRelationshipTableMap::COL_ID_MERCHANT_RELATIONSHIP;
    protected const COLUMN_MERCHANT_NAME = SpyMerchantTableMap::COL_NAME;
    protected const COLUMN_BUSINESS_UNIT_OWNER_NAME = SpyCompanyBusinessUnitTableMap::COL_NAME;

    protected const FK_MERCHANT_RELATIONSHIP = SpyProductListTableMap::COL_FK_MERCHANT_RELATIONSHIP;

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
            static::COLUMN_MERCHANT_RELATION_ID => 'ID Merchant Relation',
            static::COLUMN_MERCHANT_NAME => 'Merchant Name',
            static::COLUMN_BUSINESS_UNIT_OWNER_NAME => 'Business Unit Owner Name',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQuery(): QueryCriteriaTransfer
    {
        return $this
            ->getFactory()
            ->getProductListQueryExpander()
            ->buildProductListMerchantQueryCriteria();
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
        $searchableAndSortableFields = [
            static::COLUMN_MERCHANT_RELATION_ID,
            //static::COLUMN_MERCHANT_NAME,
            //static::COLUMN_BUSINESS_UNIT_OWNER_NAME,
        ];

        $config->setSortable( array_merge(
            $config->getSortable(),
            $searchableAndSortableFields
        ));

        $config->setSearchable( array_merge(
            $config->getSortable(),
            $searchableAndSortableFields
        ));

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
        if (!$item[static::FK_MERCHANT_RELATIONSHIP]) {
            return [
                static::COLUMN_MERCHANT_RELATION_ID => '',
                static::COLUMN_MERCHANT_NAME => '',
                static::COLUMN_BUSINESS_UNIT_OWNER_NAME => '',
            ];
        }

        $merchantRelationshipTransfer = $this->getMerchantRelationshipById($item[static::FK_MERCHANT_RELATIONSHIP]);

        return [
            static::COLUMN_MERCHANT_RELATION_ID => $item[static::FK_MERCHANT_RELATIONSHIP],
            static::COLUMN_MERCHANT_NAME => $merchantRelationshipTransfer->getMerchant()->getName(),
            static::COLUMN_BUSINESS_UNIT_OWNER_NAME => $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getName(),
        ];
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function getMerchantRelationshipById(int $idMerchantRelationship): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())->setIdMerchantRelationship($idMerchantRelationship);

        return $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->getMerchantRelationshipById($merchantRelationshipTransfer);
    }
}
