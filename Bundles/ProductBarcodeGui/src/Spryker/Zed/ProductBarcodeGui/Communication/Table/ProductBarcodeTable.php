<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication\Table;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleInterface;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeInterface;
use Spryker\Zed\ProductBarcodeGui\Persistence\ProductBarcodeGuiQueryContainerInterface;

/**
 * @uses SpyProduct
 * @uses SpyProductQuery
 * @uses ProductBarcodeGuiQueryContainerInterface
 */
class ProductBarcodeTable extends AbstractTable
{
    protected const COL_ID_PRODUCT = 'id_product';
    protected const COL_PRODUCT_SKU = 'sku';
    protected const COL_PRODUCT_NAME = 'name';
    protected const COL_BARCODE = 'barcode';

    protected const BARCODE_IMAGE_TEMPLATE = '<img src="%s,%s">';

    /**
     * @var \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeInterface
     */
    protected $productBarcodeFacade;

    /**
     * @var \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductBarcodeGui\Persistence\ProductBarcodeGuiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToProductBarcodeFacadeInterface $barcodeServiceBridge
     * @param \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleInterface $localeFacadeBridge
     * @param \Spryker\Zed\ProductBarcodeGui\Persistence\ProductBarcodeGuiQueryContainerInterface $queryContainer
     */
    public function __construct(
        ProductBarcodeGuiToProductBarcodeFacadeInterface $barcodeServiceBridge,
        ProductBarcodeGuiToLocaleInterface $localeFacadeBridge,
        ProductBarcodeGuiQueryContainerInterface $queryContainer
    ) {
        $this->productBarcodeFacade = $barcodeServiceBridge;
        $this->localeFacade = $localeFacadeBridge;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_PRODUCT => 'Product ID',
            static::COL_PRODUCT_NAME => 'Product Name',
            static::COL_PRODUCT_SKU => 'SKU',
            static::COL_BARCODE => 'Barcode',
        ]);

        $config->setSearchable([
            static::COL_ID_PRODUCT,
            static::COL_PRODUCT_NAME,
            static::COL_PRODUCT_SKU,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT,
            static::COL_PRODUCT_NAME,
            static::COL_PRODUCT_SKU,
        ]);

        $config->setRawColumns([
            static::COL_BARCODE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery(
            $this->prepareQuery(),
            $config,
            true
        );

        $results = [];

        foreach ($queryResults as $queryItem) {
            $results[] = $this->generateItem($queryItem);
        }

        return $results;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function prepareQuery(): SpyProductQuery
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();

        return $this->queryContainer->prepareTableQuery($localeTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $product
     *
     * @return array
     */
    protected function generateItem(SpyProduct $product): array
    {
        $sku = $product->getSku();
        $productName = $product->getVirtualColumn(ProductBarcodeGuiQueryContainerInterface::COL_PRODUCT_NAME);

        return [
            static::COL_ID_PRODUCT => $product->getIdProduct(),
            static::COL_PRODUCT_SKU => $sku,
            static::COL_PRODUCT_NAME => $productName,
            static::COL_BARCODE => $this->getBarcodeImageBySku($sku),
        ];
    }

    /**
     * @return int
     */
    protected function getCurrentLocaleId(): int
    {
        return $this->localeFacade
            ->getCurrentLocale()
            ->getIdLocale();
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function getBarcodeImageBySku(string $sku): string
    {
        $productTransfer = new ProductConcreteTransfer();
        $productTransfer->setSku($sku);
        $barcodeResponseTransfer = $this->productBarcodeFacade->generateBarcode($productTransfer);

        return sprintf(
            static::BARCODE_IMAGE_TEMPLATE,
            $barcodeResponseTransfer->getEncoding(),
            $barcodeResponseTransfer->getCode()
        );
    }
}
