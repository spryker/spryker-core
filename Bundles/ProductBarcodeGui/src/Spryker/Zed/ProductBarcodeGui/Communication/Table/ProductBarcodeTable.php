<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Communication\Table;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridgeInterface;
use Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceBridgeInterface;

class ProductBarcodeTable extends AbstractTable
{
    protected const COL_ID_PRODUCT = 'id_product';
    protected const COL_PRODUCT_SKU = 'sku';

    protected const COL_PRODUCT_NAME = 'product_name';
    protected const COL_BARCODE = 'barcode';

    /**
     * @var \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    protected $barcodeServiceBridge;

    /**
     * @var \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridgeInterface
     */
    protected $localeFacadeBridge;

    /**
     * @param \Spryker\Zed\ProductBarcodeGui\Dependency\Service\ProductBarcodeGuiToBarcodeServiceBridgeInterface $barcodeServiceBridge
     * @param \Spryker\Zed\ProductBarcodeGui\Dependency\Facade\ProductBarcodeGuiToLocaleBridgeInterface $localeFacadeBridge
     */
    public function __construct(ProductBarcodeGuiToBarcodeServiceBridgeInterface $barcodeServiceBridge, ProductBarcodeGuiToLocaleBridgeInterface $localeFacadeBridge)
    {
        $this->barcodeServiceBridge = $barcodeServiceBridge;
        $this->localeFacadeBridge = $localeFacadeBridge;
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
        $query = SpyProductQuery::create()
            ->innerJoinSpyProductLocalizedAttributes()
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByFkLocale($this->getCurrentLocaleId())
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_PRODUCT_NAME);

        return $query;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $product
     *
     * @return array
     */
    protected function generateItem(SpyProduct $product): array
    {
        $sku = $product->getSku();

        $barcodeTransfer = $this->generateBarcode($sku);

        return [
            static::COL_ID_PRODUCT => $product->getIdProduct(),
            static::COL_PRODUCT_SKU => $sku,
            static::COL_PRODUCT_NAME => $product->getVirtualColumn(static::COL_PRODUCT_NAME),
            static::COL_BARCODE => sprintf('<img src="%s,%s">', $barcodeTransfer->getEncoding(), $barcodeTransfer->getCode()),
        ];
    }

    /**
     * @return int
     */
    protected function getCurrentLocaleId(): int
    {
        return $this->localeFacadeBridge
            ->getCurrentLocale()
            ->getIdLocale();
    }

    /**
     * @param string $text
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function generateBarcode(string $text, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this->barcodeServiceBridge->generateBarcode($text, $generatorPlugin);
    }
}
