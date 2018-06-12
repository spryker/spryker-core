<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\Map\SpyProductPackagingUnitTypeTableMap;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleBridge;
use Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface;

class ProductPackagingUnitTypeTable extends AbstractTable
{
    protected const TABLE_IDENTIFIER = 'product-packaging-unit-type-table';
    protected const COL_ID = SpyProductPackagingUnitTypeTableMap::COL_ID_PRODUCT_PACKAGING_UNIT_TYPE;
    protected const COL_NAME = SpyProductPackagingUnitTypeTableMap::COL_NAME;
    protected const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface
     */
    protected $productPackagingUnitGuiRepository;

    /**
     * @var \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnitGui\Persistence\ProductPackagingUnitGuiRepositoryInterface $productPackagingUnitGuiRepository
     * @param \Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductPackagingUnitGuiRepositoryInterface $productPackagingUnitGuiRepository,
        ProductPackagingUnitGuiToLocaleBridge $localeFacade
    ) {
        $this->productPackagingUnitGuiRepository = $productPackagingUnitGuiRepository;
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
            static::COL_NAME => 'Name',
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
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function prepareQuery(): SpyProductPackagingUnitTypeQuery
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        return $this->prepareTableQuery($localeTransfer);
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
            $this->prepareQuery(),
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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function prepareTableQuery(LocaleTransfer $localeTransfer): SpyProductPackagingUnitTypeQuery
    {
        return $this->productPackagingUnitGuiRepository
            ->queryProductPackagingUnitTypes();
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
                ProductPackagingUnitTypeTableConstantsInterface::URL_PRODUCT_PACKAGING_UNIT_TYPE_EDIT,
                [
                    ProductPackagingUnitTypeTableConstantsInterface::REQUEST_ID_PRODUCT_PACKAGING_UNIT_TYPE => $idProductPackagingUnitType,
                ]
            ),
            'Edit'
        );
    }
}
