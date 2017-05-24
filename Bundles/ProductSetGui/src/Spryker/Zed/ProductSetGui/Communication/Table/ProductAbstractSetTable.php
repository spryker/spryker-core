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
use Spryker\Zed\Money\Business\MoneyFacade;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductSetGui\Communication\Controller\AbstractProductSetController;
use Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainer;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface;

// TODO: rename to ProductAbstractSetUpdateTable
class ProductAbstractSetTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-abstract-set-table';
    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_PREVIEW = 'preview';
    const COL_SKU = 'sku';
    const COL_NAME = ProductSetGuiQueryContainer::COL_ALIAS_NAME;
    const COL_PRICE = 'price';
    const COL_STATUS = 'status';
    const COL_ORDER = ProductSetGuiQueryContainer::COL_ALIAS_POSITION;
    const COL_CHECKBOX = 'checkbox';

    /**
     * @var \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface
     */
    protected $productSetGuiQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface
     */
    protected $utilEncodingService;

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
     * @param \Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     */
    public function __construct(
        ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer,
        ProductSetGuiToUtilEncodingInterface $utilEncodingService,
        LocaleTransfer $localeTransfer,
        $idProductSet
    ) {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeTransfer = $localeTransfer;
        $this->idProductSet = $idProductSet;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $urlSuffix = sprintf('?%s=%d', AbstractProductSetController::PARAM_ID, $this->idProductSet);
        $this->defaultUrl = self::TABLE_IDENTIFIER . $urlSuffix;
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);

        $config->setHeader([
            self::COL_ID_PRODUCT_ABSTRACT => 'ID',
            self::COL_PREVIEW => 'Preview',
            self::COL_SKU => 'SKU',
            self::COL_NAME => 'Name',
            self::COL_PRICE => 'Price',
            self::COL_STATUS => 'Status',
            self::COL_ORDER => 'Order',
            self::COL_CHECKBOX => 'Selected',
        ]);

        $config->setSortable([
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_SKU,
            self::COL_NAME,
            self::COL_ORDER,
        ]);

        $config->setSearchable([
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_SKU,
            self::COL_NAME,
        ]);

        $config->setRawColumns([
            self::COL_PREVIEW,
            self::COL_CHECKBOX,
            self::COL_STATUS,
            self::COL_ORDER,
        ]);

        $config->setDefaultSortField(self::COL_ID_PRODUCT_ABSTRACT, TableConfiguration::SORT_ASC);

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
            self::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            self::COL_PREVIEW => $this->getProductPreview($productAbstractEntity),
            self::COL_SKU => $productAbstractEntity->getSku(),
            self::COL_NAME => $productAbstractEntity->getVirtualColumn(self::COL_NAME),
            self::COL_PRICE => $this->getProductPrice($productAbstractEntity),
            self::COL_STATUS => $this->getAbstractProductStatusLabel($productAbstractEntity),
            self::COL_ORDER => $this->getOrderField($productAbstractEntity),
            self::COL_CHECKBOX => $this->getSelectField($productAbstractEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getOrderField(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<input type="text" value="%2$d" id="product_order_%1$d" class="product_order" size="4" data-info="%1$s">',
            $productAbstractEntity->getIdProductAbstract(),
            $productAbstractEntity->getVirtualColumn(self::COL_ORDER)
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getSelectField(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<input id="product_checkbox_%1$d" class="product_checkbox" type="checkbox" checked="checked" data-id="%1$s">',
            $productAbstractEntity->getIdProductAbstract()
        );
    }

    // TODO: extract image, price, status related things

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getProductPreview(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<img src="%s">',
            $this->getProductPreviewUrl($productAbstractEntity)
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return null|string
     */
    protected function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity)
    {
        $productImageFacade = new ProductImageFacade(); // FIXME

        $productImageSetTransferCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId($productAbstractEntity->getIdProductAbstract());

        foreach ($productImageSetTransferCollection as $productImageSetTransfer) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $previewUrl = $productImageTransfer->getExternalUrlSmall();

                if ($previewUrl) {
                    return $previewUrl;
                }
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    protected function getProductPrice(SpyProductAbstract $productAbstractEntity)
    {
        $priceFacade = new PriceFacade(); // FIXME
        $priceProductTransfer = $priceFacade->findProductAbstractPrice($productAbstractEntity->getIdProductAbstract());

        if (!$priceProductTransfer) {
            return null;
        }

        $moneyFacade = new MoneyFacade(); // FIXME
        $moneyTransfer = $moneyFacade->fromInteger($priceProductTransfer->getPrice());

        return $moneyFacade->formatWithSymbol($moneyTransfer);
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
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        if (!$status) {
            return '<span class="label label-danger">Inactive</span>';
        }

        return '<span class="label label-info">Active</span>';
    }

}
