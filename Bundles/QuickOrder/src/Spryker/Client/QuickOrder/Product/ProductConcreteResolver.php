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
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface;
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
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface $localeClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        QuickOrderToProductStorageClientInterface $productStorageClient,
        QuickOrderToLocaleClientInterface $localeClient,
        QuickOrderToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
        $this->productQuantityStorageClient = $productQuantityStorageClient;
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
        $productConcreteTransfer = $this->setProductConcreteRestrictions($productConcreteTransfer);
        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())->setName($productConcreteStorageData[static::NAME]);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function setProductConcreteRestrictions(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productQuantityStorageTransfer = $this->productQuantityStorageClient
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            $productConcreteTransfer->setMinQuantity(1);
            $productConcreteTransfer->setQuantityInterval(1);

            return $productConcreteTransfer;
        }

        $minQuantity = $productQuantityStorageTransfer->getQuantityMin() ?? 1;
        $maxQuantity = $productQuantityStorageTransfer->getQuantityMax();
        $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? 1;
        $productConcreteTransfer->setMinQuantity($minQuantity);
        $productConcreteTransfer->setMaxQuantity($maxQuantity);
        $productConcreteTransfer->setQuantityInterval($quantityInterval);

        return $productConcreteTransfer;
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
        $productConcreteTransfer = $this->setProductConcreteRestrictions($productConcreteTransfer);

        return $productConcreteTransfer
            ->setFkProductAbstract($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])
            ->addLocalizedAttributes($localizedAttributesTransfer);
    }
}
