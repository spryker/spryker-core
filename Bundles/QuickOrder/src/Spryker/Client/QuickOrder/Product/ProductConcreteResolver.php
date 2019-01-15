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
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface;

class ProductConcreteResolver implements ProductConcreteResolverInterface
{
    protected const MAPPING_TYPE_SKU = 'sku';
    protected const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const SKU = 'sku';
    protected const NAME = 'name';
    protected const ERROR_MESSAGE_INVALID_SKU = 'quick-order.upload-order.errors.upload-order-invalid-sku-item';

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface[]
     */
    protected $quickOrderValidationPlugins;

    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface[] $quickOrderValidatorPlugins
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[] $productConcreteExpanderPlugins
     */
    public function __construct(
        QuickOrderToProductStorageClientInterface $productStorageClient,
        array $quickOrderValidatorPlugins,
        array $productConcreteExpanderPlugins
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->quickOrderValidationPlugins = $quickOrderValidatorPlugins;
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
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
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function addProductsToQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            if (empty($quickOrderItemTransfer->getSku())) {
                continue;
            }

            $productConcreteTransfer = $this->findProductConcreteBySku($quickOrderItemTransfer->getSku());

            if ($productConcreteTransfer === null) {
                $productConcreteTransfer = (new ProductConcreteTransfer())->setSku($quickOrderItemTransfer->getSku());
                $quickOrderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_INVALID_SKU);
            }

            $quickOrderItemTransfer->setProductConcrete($productConcreteTransfer);
            $this->validateQuickOrderItem($quickOrderItemTransfer);
            $this->expandQuickOrderItem($quickOrderItemTransfer);
        }

        return $quickOrderTransfer;
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
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        foreach ($this->quickOrderValidationPlugins as $quickOrderValidationPlugin) {
            $quickOrderValidationPlugin->validateQuickOrderItemProduct($quickOrderItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function expandQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        if ($quickOrderItemTransfer->getProductConcrete()->getIdProductConcrete()) {
            foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
                $expandedProductConcrete = $productConcreteExpanderPlugin->expand([$quickOrderItemTransfer->getProductConcrete()]);
            }

            $quickOrderItemTransfer->setProductConcrete($expandedProductConcrete[0]);
        }
    }
}
