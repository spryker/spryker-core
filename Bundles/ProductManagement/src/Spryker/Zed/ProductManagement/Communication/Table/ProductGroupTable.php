<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface;
use Spryker\Zed\ProductManagement\Communication\Controller\EditController;
use Spryker\Zed\ProductManagement\Communication\Controller\ViewController;

class ProductGroupTable extends AbstractProductTable
{
    public const COL_ID_PRODUCT_GROUP = 'id_product_group';
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const COL_NAME = 'name';
    public const COL_SKU = 'sku';
    public const COL_STATUS = 'status';
    public const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    protected $productGroupQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var int
     */
    protected $idProductAbstract;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface $productGroupQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductAbstract
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductGroupQueryContainerInterface $productGroupQueryContainer,
        LocaleTransfer $localeTransfer,
        $idProductAbstract
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->idProductAbstract = $idProductAbstract;
        $this->defaultUrl = sprintf(
            'productGroupTable?%s=%d',
            ViewController::PARAM_ID_PRODUCT_ABSTRACT,
            $idProductAbstract
        );
        $this->productGroupQueryContainer = $productGroupQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT_GROUP => 'Product Group ID',
            static::COL_ID_PRODUCT_ABSTRACT => 'Product ID',
            static::COL_NAME => 'Name',
            static::COL_SKU => 'Sku',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_GROUP,
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU,
            static::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productQueryContainer
            ->queryProductAbstract()
            ->useSpyProductAbstractGroupQuery()
                ->filterByFkProductGroup_In($this->getIdProductGroups())
            ->endUse()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($this->localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_GROUP, static::COL_ID_PRODUCT_GROUP);

        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productAbstractEntity) {
            $productAbstractCollection[] = $this->generateItem($productAbstractEntity);
        }

        return $productAbstractCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function generateItem(SpyProductAbstract $productAbstractEntity)
    {
        return [
            static::COL_ID_PRODUCT_GROUP => $productAbstractEntity->getVirtualColumn(static::COL_ID_PRODUCT_GROUP),
            static::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_NAME => $productAbstractEntity->getVirtualColumn(static::COL_NAME),
            static::COL_STATUS => $this->getAbstractProductStatusLabel($productAbstractEntity),
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productAbstractEntity)),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $item
     *
     * @return array
     */
    protected function createActionColumn(SpyProductAbstract $item)
    {
        $urls = [];

        $urls[] = $this->generateViewButton(
            Url::generate('/product-management/view', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]),
            'View'
        );

        $urls[] = $this->generateEditButton(
            Url::generate('/product-management/edit', [
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $item->getIdProductAbstract(),
            ]),
            'Edit'
        );

        return $urls;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getAbstractProductStatusLabel(SpyProductAbstract $productAbstractEntity)
    {
        $isActive = false;
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                $isActive = true;
            }
        }

        return $this->getStatusLabel($isActive);
    }

    /**
     * @return array
     */
    protected function getIdProductGroups()
    {
        $idProductGroups = [];
        $productAbstractGroups = $this->productGroupQueryContainer
            ->queryProductAbstractGroupsByIdProductAbstract($this->idProductAbstract)
            ->find();

        foreach ($productAbstractGroups as $productAbstractGroupEntity) {
            $idProductGroups[] = $productAbstractGroupEntity->getFkProductGroup();
        }

        return $idProductGroups;
    }
}
