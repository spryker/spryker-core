<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSetGui\Communication\Controller\AbstractProductSetController;
use Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainer;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface;

class ProductAbstractSetViewTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-abstract-set-view-table';

    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const COL_IMAGE = 'image';
    public const COL_DETAILS = 'details';
    public const COL_NAME = ProductSetGuiQueryContainer::COL_ALIAS_NAME;
    public const COL_POSITION = ProductSetGuiQueryContainer::COL_ALIAS_POSITION;

    /**
     * @var \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface
     */
    protected $productSetGuiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface
     */
    protected $productAbstractTableHelper;

    /**
     * @var int
     */
    protected $idProductSet;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer
     * @param \Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface $productAbstractTableHelper
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     */
    public function __construct(
        ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer,
        ProductAbstractTableHelperInterface $productAbstractTableHelper,
        LocaleTransfer $localeTransfer,
        $idProductSet
    ) {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->idProductSet = $idProductSet;
        $this->productAbstractTableHelper = $productAbstractTableHelper;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $urlSuffix = sprintf('?%s=%d', AbstractProductSetController::PARAM_ID, $this->idProductSet);
        $this->defaultUrl = static::TABLE_IDENTIFIER . $urlSuffix;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $this->disableSearch();

        $config->setHeader([
            static::COL_ID_PRODUCT_ABSTRACT => 'ID',
            static::COL_IMAGE => 'Preview',
            static::COL_DETAILS => 'Product details',
            static::COL_POSITION => 'Position',
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_POSITION,
        ]);

        $config->setRawColumns([
            static::COL_IMAGE,
            static::COL_DETAILS,
        ]);

        $config->setDefaultSortField(static::COL_POSITION, TableConfiguration::SORT_ASC);
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
        $query = $this->productSetGuiQueryContainer->queryProductAbstractByIdProductSet($this->idProductSet, $this->localeTransfer);

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
        return [
            static::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            static::COL_IMAGE => $this->productAbstractTableHelper->getProductPreview($productAbstractEntity),
            static::COL_DETAILS => $this->generateDetailsColumn($productAbstractEntity),
            static::COL_POSITION => $productAbstractEntity->getVirtualColumn(static::COL_POSITION),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function generateDetailsColumn(SpyProductAbstract $productAbstractEntity)
    {
        $rawContent = '<p>' .
            '<strong><a href="%s">%s</a></strong><br />' .
            '<small>SKU: %s</small><br/>' .
            '<small>Price: %s</small>' .
            '</p> %s';

        $content = sprintf(
            $rawContent,
            Url::generate('/product-management/view', ['id-product-abstract' => $productAbstractEntity->getIdProductAbstract()])->build(),
            $productAbstractEntity->getVirtualColumn(static::COL_NAME),
            $productAbstractEntity->getSku(),
            $this->productAbstractTableHelper->getProductPrice($productAbstractEntity),
            $this->productAbstractTableHelper->getAbstractProductStatusLabel($productAbstractEntity)
        );

        return $content;
    }
}
