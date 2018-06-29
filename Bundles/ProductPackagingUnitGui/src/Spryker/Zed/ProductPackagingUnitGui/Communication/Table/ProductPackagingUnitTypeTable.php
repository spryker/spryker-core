<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Table;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductPackagingUnitGui\ProductPackagingUnitGuiConfig;

class ProductPackagingUnitTypeTable extends AbstractTable
{
    protected const TABLE_IDENTIFIER = 'product-packaging-unit-type-table';
    /**
     * @uses \Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTypeTableMap::COL_ID_PRODUCT_PACKAGING_UNIT_TYPE
     */
    protected const COL_ID = 'id_product_packaging_unit_type';
    /**
     * @uses \Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTypeTableMap::COL_NAME
     */
    protected const COL_NAME = 'name';
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected $packagingUnitTypeQuery;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery $packagingUnitTypeQuery
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SpyProductPackagingUnitTypeQuery $packagingUnitTypeQuery,
        ProductPackagingUnitGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->packagingUnitTypeQuery = $packagingUnitTypeQuery;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config): void
    {
        $config->setHeader([
            static::COL_ID => 'Id',
            static::COL_NAME => 'Key',
            static::COL_ACTIONS => 'Actions',
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config): void
    {
        $config->addRawColumn(static::COL_ACTIONS);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config): void
    {
        $config->setSortable([
            static::COL_ID,
            static::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config): void
    {
        $config->setSearchable([
            static::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $results = [];
        /** @var \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType[] $productPackagingUnitTypes */
        $productPackagingUnitTypes = $this->runQuery(
            $this->packagingUnitTypeQuery,
            $config,
            true
        );

        foreach ($productPackagingUnitTypes as $productPackagingUnitType) {
            $results[] = [
                static::COL_ID => $productPackagingUnitType->getIdProductPackagingUnitType(),
                static::COL_NAME => $productPackagingUnitType->getName(),
                static::COL_ACTIONS => $this->createActionButtons($productPackagingUnitType),
            ];
        }
        unset($productPackagingUnitTypes);

        return $results;
    }

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType $productPackagingUnitType
     *
     * @return string
     */
    protected function createActionButtons(SpyProductPackagingUnitType $productPackagingUnitType): string
    {
        $idProductPackagingUnitType = $productPackagingUnitType->getIdProductPackagingUnitType();
        $actionButtons = [
            $this->createEditButton($idProductPackagingUnitType),
            $this->createDeleteButton($idProductPackagingUnitType),
        ];

        return implode(' ', $actionButtons);
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return string
     */
    protected function createEditButton($idProductPackagingUnitType): string
    {
        return $this->generateEditButton(
            Url::generate(
                ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_EDIT,
                [
                    ProductPackagingUnitGuiConfig::REQUEST_PARAM_ID_PRODUCT_PACKAGING_UNIT_TYPE => $idProductPackagingUnitType,
                ]
            ),
            'Edit'
        );
    }

    /**
     * @param int $idProductPackagingUnitType
     *
     * @return string
     */
    protected function createDeleteButton($idProductPackagingUnitType): string
    {
        return $this->generateEditButton(
            Url::generate(
                ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_DELETE,
                [
                    ProductPackagingUnitGuiConfig::REQUEST_PARAM_ID_PRODUCT_PACKAGING_UNIT_TYPE => $idProductPackagingUnitType,
                    ProductPackagingUnitGuiConfig::REQUEST_PARAM_REDIRECT_URL => ProductPackagingUnitGuiConfig::URL_PRODUCT_PACKAGING_UNIT_TYPE_LIST,
                ]
            ),
            'Delete'
        );
    }
}
