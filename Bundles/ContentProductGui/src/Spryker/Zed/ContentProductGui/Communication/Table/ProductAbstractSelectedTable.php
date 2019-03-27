<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ContentProductGui\Communication\Controller\AbstractProductController;
use Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductAbstractSelectedTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-abstract-selected-table';
    public const TABLE_CLASS = 'product-abstract-selected-table gui-table-data';
    public const BASE_URL = '/content-product-gui/abstract-product/';

    public const COL_ID_PRODUCT_ABSTRACT = 'ID';
    public const COL_SKU = 'SKU';
    public const COL_IMAGE = 'Image';
    public const COL_NAME = 'Name';
    public const COL_STORIES = 'Stories';
    public const COL_STATUS = 'Status';
    public const COL_DELETE = 'Delete';

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
     * @var array
     */
    protected $idProductAbstracts;

    /**
     * @param \Spryker\Zed\ContentProductGui\Dependency\QueryContainer\ContentProductGuiToProductInterface $productQueryContainer
     * @param \Spryker\Zed\ContentProductGui\Communication\Table\Helper\ProductAbstractTableHelperInterface $productAbstractTableHelper
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierPostfix
     * @param array $idProductAbstracts
     */
    public function __construct(
        ContentProductGuiToProductInterface $productQueryContainer,
        ProductAbstractTableHelperInterface $productAbstractTableHelper,
        LocaleTransfer $localeTransfer,
        ?string $identifierPostfix,
        array $idProductAbstracts
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productAbstractTableHelper = $productAbstractTableHelper;
        $this->localeTransfer = $localeTransfer;
        $this->identifierPostfix = $identifierPostfix;
        $this->idProductAbstracts = $idProductAbstracts;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $urlSuffix = '';
        if (($this->idProductAbstracts)) {
            $urlSuffix = '?' . http_build_query([AbstractProductController::PARAM_IDS => $this->idProductAbstracts]);
        }

        $this->baseUrl = static::BASE_URL;
        $this->defaultUrl = static::TABLE_IDENTIFIER . $urlSuffix;
        $this->tableClass = static::TABLE_CLASS;
        $this->setTableIdentifier(sprintf('%s-%s', static::TABLE_IDENTIFIER, $this->identifierPostfix));

        $this->disableSearch();

        $config->setHeader([
            static::COL_ID_PRODUCT_ABSTRACT => static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU => static::COL_SKU,
            static::COL_IMAGE => static::COL_IMAGE,
            static::COL_NAME => static::COL_NAME,
            static::COL_STORIES => static::COL_STORIES,
            static::COL_STATUS => static::COL_STATUS,
            static::COL_DELETE => static::COL_DELETE,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_STORIES,
            static::COL_STATUS,
            static::COL_DELETE,
        ]);

        $config->setStateSave(false);

        return $config;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function newTableConfiguration()
    {
        $tableConfiguration = parent::newTableConfiguration();
        $tableConfiguration->setServerSide(false);
        $tableConfiguration->setPaging(false);
        $tableConfiguration->setOrdering(false);

        return $tableConfiguration;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $results = [];
        if ($this->idProductAbstracts) {
            $idProductAbstracts = array_values($this->idProductAbstracts);
            $query = $this->productQueryContainer->queryProductAbstract()
                ->filterByIdProductAbstract_In($idProductAbstracts)
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($this->localeTransfer->getIdLocale())
                ->endUse();

            $queryResults = $this->runQuery($query, $config, true);

            /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
            foreach ($queryResults as $productAbstractEntity) {
                $index = array_search($productAbstractEntity->getIdProductAbstract(), $idProductAbstracts);
                $results[$index] = $this->formatRow($productAbstractEntity);
            }
            ksort($results);
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
            static::COL_DELETE => $this->productAbstractTableHelper->getDeleteButton($productAbstractEntity),
        ];
    }
}
