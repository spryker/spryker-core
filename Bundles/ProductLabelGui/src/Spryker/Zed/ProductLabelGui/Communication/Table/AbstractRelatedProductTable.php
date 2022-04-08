<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface;

abstract class AbstractRelatedProductTable extends AbstractTable
{
    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT_LABEL = 'id-product-label';

    public const COL_PRODUCT_ABSTRACT_NAME = SpyProductAbstractLocalizedAttributesTableMap::COL_NAME;

    public const COL_PRODUCT_ABSTRACT_CATEGORIES = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV;

    public const COL_PRODUCT_ABSTRACT_PRICE = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_PRICE;

    public const COL_PRODUCT_ABSTRACT_STATUS = RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV;

    /**
     * @var string
     */
    public const COL_SELECT_CHECKBOX = 'select-checkbox';

    /**
     * @var array<string>
     */
    protected const DB_BOOLEAN_TRUE_VALUES = ['1', 'true'];

    /**
     * @var \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface
     */
    protected $tableQueryBuilder;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface
     */
    protected $productLabelGuiRepository;

    /**
     * @var int|null
     */
    protected $idProductLabel;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Communication\Table\RelatedProductTableQueryBuilderInterface $tableQueryBuilder
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiRepositoryInterface $productLabelGuiRepository
     * @param int|null $idProductLabel
     */
    public function __construct(
        RelatedProductTableQueryBuilderInterface $tableQueryBuilder,
        ProductLabelGuiToMoneyInterface $moneyFacade,
        ProductLabelGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductLabelGuiToLocaleInterface $localeFacade,
        ProductLabelGuiRepositoryInterface $productLabelGuiRepository,
        ?int $idProductLabel = null
    ) {
        $this->tableQueryBuilder = $tableQueryBuilder;
        $this->moneyFacade = $moneyFacade;
        $this->priceProductFacade = $priceProductFacade;
        $this->localeFacade = $localeFacade;
        $this->productLabelGuiRepository = $productLabelGuiRepository;
        $this->idProductLabel = $idProductLabel;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->getQuery();

        /** @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractEntities */
        $productAbstractEntities = $this->runQuery($query, $config, true);

        $productAbstractIds = $productAbstractEntities->getPrimaryKeys();

        $categoryNames = $this->productLabelGuiRepository->getCategoryNamesGroupedByIdProductAbstract(
            $productAbstractIds,
            $this->localeFacade->getCurrentLocale()->getIdLocale(),
        );

        $additionalRelationsCount = $this->getAdditionalRelationsCount($productAbstractIds);

        $rows = [];
        foreach ($productAbstractEntities as $productAbstractEntity) {
            $rows[] = $this->prepareRowData($productAbstractEntity, $categoryNames, $additionalRelationsCount);
        }

        return $rows;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    abstract protected function getQuery();

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelGui\Communication\Table\AbstractRelatedProductTable::prepareRowData()} instead.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function getRow(SpyProductAbstract $productAbstractEntity)
    {
        return [
            static::COL_PRODUCT_ABSTRACT_NAME => $this->getNameColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_CATEGORIES => $this->getCategoriesColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_PRICE => $this->getPriceColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_STATUS => $this->getStatusColumn($productAbstractEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param array<int, array> $categoryNames
     * @param array<int, int> $additionalRelationsCount
     *
     * @return array
     */
    protected function prepareRowData(SpyProductAbstract $productAbstractEntity, array $categoryNames, array $additionalRelationsCount = []): array
    {
        return [
            static::COL_PRODUCT_ABSTRACT_NAME => $this->getNameColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_CATEGORIES => $this->getCategoryNameColumn($categoryNames, $productAbstractEntity->getIdProductAbstract()),
            static::COL_PRODUCT_ABSTRACT_PRICE => $this->getPriceColumn($productAbstractEntity),
            static::COL_PRODUCT_ABSTRACT_STATUS => $this->getStatusColumn($productAbstractEntity),
        ];
    }

    /**
     * @param array<int, array> $categoryNames
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getCategoryNameColumn(array $categoryNames, int $idProductAbstract): string
    {
        if (!isset($categoryNames[$idProductAbstract])) {
            return '';
        }

        return implode(', ', $categoryNames[$idProductAbstract]);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getNameColumn(SpyProductAbstract $productAbstractEntity)
    {
        return $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_NAME,
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getPriceColumn(SpyProductAbstract $productAbstractEntity)
    {
        $price = $this->priceProductFacade->findPriceBySku($productAbstractEntity->getSku());

        if ($price === null) {
            return 'N/A';
        }

        $moneyTransfer = $this->moneyFacade->fromInteger($price);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getCategoriesColumn(SpyProductAbstract $productAbstractEntity)
    {
        $categoriesCsv = $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_ABSTRACT_CATEGORY_NAMES_CSV,
        );
        $categories = explode(',', $categoriesCsv);
        $categories = array_unique($categories);

        return implode(', ', $categories);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getStatusColumn(SpyProductAbstract $productAbstractEntity)
    {
        $statesCsv = $productAbstractEntity->getVirtualColumn(
            RelatedProductTableQueryBuilder::RESULT_FIELD_PRODUCT_CONCRETE_STATES_CSV,
        );
        $states = explode(',', $statesCsv);
        $isActive = (bool)array_intersect(static::DB_BOOLEAN_TRUE_VALUES, $states);

        $statusName = $isActive ? 'Active' : 'Inactive';
        $statusCssClass = $isActive ? 'label-info' : 'label-danger';

        return $this->generateLabel($statusName, $statusCssClass);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, int>
     */
    protected function getAdditionalRelationsCount(array $productAbstractIds): array
    {
        return [];
    }
}
