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

class ProductTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-table';
    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_PREVIEW = 'preview';
    const COL_SKU = 'sku';
    const COL_NAME = ProductSetGuiQueryContainer::COL_ALIAS_NAME;
    const COL_PRICE = 'price';
    const COL_STATUS = 'status';
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
    protected $idProductSetGuiGroup;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer
     * @param \Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingInterface $utilEncodingService
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int|null $idProductSetGuiGroup
     */
    public function __construct(
        ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer,
        ProductSetGuiToUtilEncodingInterface $utilEncodingService,
        LocaleTransfer $localeTransfer,
        $idProductSetGuiGroup = null
    ) {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->idProductSetGuiGroup = (int)$idProductSetGuiGroup;
        $this->localeTransfer = $localeTransfer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $urlSuffix = $this->idProductSetGuiGroup ? sprintf('?%s=%d', AbstractProductSetController::PARAM_ID, $this->idProductSetGuiGroup) : null;
        $this->defaultUrl = self::TABLE_IDENTIFIER . $urlSuffix;
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);

        $config->setHeader([
            self::COL_ID_PRODUCT_ABSTRACT => 'ID',
            self::COL_PREVIEW => 'Preview',
            self::COL_SKU => 'SKU',
            self::COL_NAME => 'Name',
            self::COL_PRICE => 'Price',
            self::COL_STATUS => 'Status',
            self::COL_CHECKBOX => 'Selected',
        ]);

        $config->setSortable([
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_SKU,
            self::COL_NAME,
        ]);

        $config->setSearchable([
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_SKU,
            self::COL_NAME,
        ]);

        $config->setRawColumns([
            self::COL_PREVIEW,
            self::COL_STATUS,
            self::COL_CHECKBOX,
        ]);

        $config->setDefaultSortField(self::COL_ID_PRODUCT_ABSTRACT, TableConfiguration::SORT_ASC);
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
        $query = $this->productSetGuiQueryContainer->queryProductAbstract($this->localeTransfer);

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
            self::COL_CHECKBOX => $this->getSelectField($productAbstractEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getSelectField(SpyProductAbstract $productAbstractEntity)
    {
        $checkbox_html = sprintf(
            '<input id="all_products_checkbox_%1$d" class="all-products-checkbox" type="checkbox" data-id="%1$s">',
            $productAbstractEntity->getIdProductAbstract()
        );

        return $checkbox_html;
    }

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
