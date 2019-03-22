<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductAbstractViewTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-abstract-view-table';
    public const TABLE_CLASS = 'product-abstract-view-table gui-table-data';
    public const BASE_URL = '/content-product-gui/abstract-product/';

    public const COL_ID_PRODUCT_ABSTRACT = 'ID';
    public const COL_SKU = 'SKU';
    public const COL_IMAGE = 'Image';
    public const COL_NAME = 'Name';
    public const COL_STORIES = 'Stories';
    public const COL_STATUS = 'Status';
    public const COL_SELECTED = 'Selected';

    /**
     * @var \Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface
     */
    protected $productAbstractTableHelper;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $identifierPostfix;

    /**
     * @param \Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface $productQueryContainer
     * @param \Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface $productAbstractTableHelper
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierPostfix
     */
    public function __construct(
        ContentProductGuiToProductInterface $productQueryContainer,
        ProductAbstractTableHelperInterface $productAbstractTableHelper,
        LocaleTransfer $localeTransfer,
        ?string $identifierPostfix
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productAbstractTableHelper = $productAbstractTableHelper;
        $this->localeTransfer = $localeTransfer;
        $this->identifierPostfix = $identifierPostfix;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = static::TABLE_IDENTIFIER;
        $this->tableClass = static::TABLE_CLASS;

        $this->setTableIdentifier(sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierPostfix));

        $config->setHeader([
            static::COL_ID_PRODUCT_ABSTRACT => static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU => static::COL_SKU,
            static::COL_IMAGE => static::COL_IMAGE,
            static::COL_NAME => static::COL_NAME,
            static::COL_STORIES => static::COL_STORIES,
            static::COL_STATUS => static::COL_STATUS,
            static::COL_SELECTED => static::COL_SELECTED,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_STORIES,
            static::COL_STATUS,
            static::COL_SELECTED,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productQueryContainer->queryProductAbstract()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse();
        $queryResults = $this->runQuery($query, $config, true);

        $results = [];
        foreach ($queryResults as $productAbstractEntity) {
            $results[] = $this->formatRow($productAbstractEntity);
        }

        return $results;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function formatRow(SpyProductAbstract $productAbstractEntity)
    {
        $idProductAbstract = $productAbstractEntity->getIdProductAbstract();

        return [
            static::COL_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_IMAGE => $this->productAbstractTableHelper->getProductPreview($productAbstractEntity),
            static::COL_NAME => $productAbstractEntity->getSpyProductAbstractLocalizedAttributess()->getFirst()->getName(),
            static::COL_STORIES => $this->productAbstractTableHelper->getStoreNames($productAbstractEntity->getSpyProductAbstractStores()->getArrayCopy()),
            static::COL_STATUS => $this->productAbstractTableHelper->getAbstractProductStatusLabel($productAbstractEntity),
            static::COL_SELECTED => $this->productAbstractTableHelper->getAddButtonField($productAbstractEntity),
        ];
    }
}
