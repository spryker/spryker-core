<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface;

class ProductConcreteResolver implements ProductConcreteResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const SKU = 'sku';
    protected const NAME = 'name';

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface $localeClient
     */
    public function __construct(QuickOrderToProductStorageClientInterface $productStorageClient, QuickOrderToLocaleClientInterface $localeClient)
    {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[] Keys are product SKUs
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array
    {
        $skus = array_map(function (QuickOrderItemTransfer $quickOrderItemTransfer) {
            return $quickOrderItemTransfer->getSku();
        }, $quickOrderTransfer->getItems()->getArrayCopy());

        $productConcreteTransfers = [];
        foreach ($skus as $index => $sku) {
            if (empty($sku)) {
                continue;
            }

            $productConcreteTransfer = $this->findProductConcreteBySku($sku);

            if ($productConcreteTransfer === null) {
                unset($quickOrderTransfer->getItems()[$index]);
                continue;
            }

            $productConcreteTransfers[] = $productConcreteTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        if ($productConcreteStorageData === null) {
            return null;
        }

        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteStorageData, true);
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setName($productConcreteStorageData[static::NAME]);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteWithProductAbstractBySku(string $sku): ?ProductConcreteTransfer
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMappingForCurrentLocale(static::MAPPING_TYPE_SKU, $sku);

        if ($productConcreteStorageData === null) {
            return null;
        }

        $productAbstractStorageData = $this->productStorageClient
            ->findProductAbstractStorageData(
                $productConcreteStorageData[static::ID_PRODUCT_ABSTRACT],
                $this->localeClient->getCurrentLocale()
            );

        if ($productAbstractStorageData === null) {
            return null;
        }

        $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productConcreteStorageData, true);
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setName($productConcreteStorageData[static::NAME]);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }
}
