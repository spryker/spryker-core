<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSetGui\Communication\Controller\AbstractProductSetController;
use Spryker\Zed\ProductSetGui\Communication\Form\Table\ProductAbstractSetUpdateFormType;
use Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToStoreFacadeInterface;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainer;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface;

class ProductAbstractSetUpdateTable extends AbstractTable
{
    /**
     * @var string
     */
    public const TABLE_IDENTIFIER = 'product-abstract-set-table';

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const COL_PREVIEW = 'preview';

    /**
     * @var string
     */
    public const COL_SKU = 'sku';

    /**
     * @var string
     */
    public const COL_NAME = ProductSetGuiQueryContainer::COL_ALIAS_NAME;

    /**
     * @var string
     */
    public const COL_PRICE = 'price';

    /**
     * @var string
     */
    public const COL_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_POSITION = ProductSetGuiQueryContainer::COL_ALIAS_POSITION;

    /**
     * @var string
     */
    public const COL_CHECKBOX = 'checkbox';

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
     * @var \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToStoreFacadeInterface
     */
    protected ProductSetGuiToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer
     * @param \Spryker\Zed\ProductSetGui\Communication\Table\Helper\ProductAbstractTableHelperInterface $productAbstractTableHelper
     * @param \Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToStoreFacadeInterface $storeFacade
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     */
    public function __construct(
        ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer,
        ProductAbstractTableHelperInterface $productAbstractTableHelper,
        ProductSetGuiToStoreFacadeInterface $storeFacade,
        LocaleTransfer $localeTransfer,
        $idProductSet
    ) {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->productAbstractTableHelper = $productAbstractTableHelper;
        $this->localeTransfer = $localeTransfer;
        $this->idProductSet = $idProductSet;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $urlSuffix = sprintf('?%s=%d', AbstractProductSetController::PARAM_ID, $this->idProductSet);
        $this->defaultUrl = static::TABLE_IDENTIFIER . $urlSuffix;
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $header = [
            static::COL_ID_PRODUCT_ABSTRACT => 'ID',
            static::COL_PREVIEW => 'Preview',
            static::COL_SKU => 'SKU',
            static::COL_NAME => 'Name',
        ];

        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            $header[static::COL_PRICE] = 'Price';
        }

        $header = array_merge($header, [
            static::COL_STATUS => 'Status',
            static::COL_POSITION => 'Position',
            static::COL_CHECKBOX => 'Selected',
        ]);

        $config->setHeader($header);

        $config->setSortable([
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU,
            static::COL_NAME,
            static::COL_POSITION,
        ]);

        $config->setSearchable([
            static::COL_ID_PRODUCT_ABSTRACT,
            static::COL_SKU,
            static::COL_NAME,
        ]);

        $config->setRawColumns([
            static::COL_PREVIEW,
            static::COL_CHECKBOX,
            static::COL_STATUS,
            static::COL_POSITION,
        ]);

        $config->setDefaultSortField(static::COL_POSITION, TableConfiguration::SORT_ASC);
        $config->setStateSave(false);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return list<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
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
     * @return array<string, mixed>
     */
    protected function formatRow(SpyProductAbstract $productAbstractEntity): array
    {
        $row = [
            static::COL_ID_PRODUCT_ABSTRACT => $this->formatInt($productAbstractEntity->getIdProductAbstract()),
            static::COL_PREVIEW => $this->productAbstractTableHelper->getProductPreview($productAbstractEntity),
            static::COL_SKU => $productAbstractEntity->getSku(),
            static::COL_NAME => $productAbstractEntity->getVirtualColumn(static::COL_NAME),
            static::COL_STATUS => $this->productAbstractTableHelper->getAbstractProductStatusLabel($productAbstractEntity),
            static::COL_POSITION => $this->getPositionField($productAbstractEntity),
            static::COL_CHECKBOX => $this->getSelectField($productAbstractEntity),
        ];

        if (!$this->storeFacade->isDynamicStoreEnabled()) {
            $row[static::COL_PRICE] = $this->productAbstractTableHelper->getProductPrice($productAbstractEntity);
        }

        return $row;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getPositionField(SpyProductAbstract $productAbstractEntity): string
    {
        $idProductAbstract = $productAbstractEntity->getIdProductAbstract();

        return $this->generateFormField(
            ProductAbstractSetUpdateFormType::class,
            ProductAbstractSetUpdateFormType::FIELD_PRODUCT_POSITION,
            [
                ProductAbstractSetUpdateFormType::OPTION_LOCALE => $this->localeTransfer->getLocaleName(),
                ProductAbstractSetUpdateFormType::OPTION_ID => sprintf('product_position_%d', $idProductAbstract),
                ProductAbstractSetUpdateFormType::OPTION_DATA_ID => $idProductAbstract,
            ],
            [
                ProductAbstractSetUpdateFormType::FIELD_PRODUCT_POSITION => $productAbstractEntity->getVirtualColumn(static::COL_POSITION),
            ],
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getSelectField(SpyProductAbstract $productAbstractEntity): string
    {
        return sprintf(
            '<input id="product_checkbox_%1$d" class="product_checkbox" type="checkbox" checked="checked" data-id="%1$s">',
            $productAbstractEntity->getIdProductAbstract(),
        );
    }
}
