<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface>
     */
    protected array $productAbstractCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface> $productAbstractCollectionExpanderPlugins
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        array $productAbstractCollectionExpanderPlugins
    ) {
        $this->productRepository = $productRepository;
        $this->productAbstractCollectionExpanderPlugins = $productAbstractCollectionExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer
    {
        $productAbstractCollectionTransfer = $this->productRepository->getProductAbstractCollection($productAbstractCriteriaTransfer);

        if (!$productAbstractCriteriaTransfer->getProductAbstractRelations()) {
            return $productAbstractCollectionTransfer;
        }

        $productAbstractIds = [];
        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstractIds[] = $productAbstractTransfer->getIdProductAbstract();
        }

        if ($productAbstractCriteriaTransfer->getProductAbstractRelations()->getWithStoreRelations()) {
            $storeRelationTransfers = $this->productRepository->getProductAbstractStoreRelations($productAbstractIds);

            foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
                if (isset($storeRelationTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()])) {
                    $productAbstractTransfer->setStoreRelation($storeRelationTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()]);
                }
            }
        }

        if ($productAbstractCriteriaTransfer->getProductAbstractRelations()->getWithVariants()) {
            $productConcreteTransfers = $this->productRepository->getProductConcreteTransfersByProductAbstractIds($productAbstractIds);

            foreach ($productConcreteTransfers as $productConcreteTransfer) {
                $productAbstractCollectionTransfer->addProductConcrete(
                    (new ProductAbstractConcreteCollectionTransfer())
                        ->setProductAbstractSku($productConcreteTransfer->getAbstractSku())
                        ->addProductConcrete($productConcreteTransfer),
                );
            }
        }

        if ($productAbstractCriteriaTransfer->getProductAbstractRelations()->getWithLocalizedAttributes()) {
            $productAbstractLocalizedAttributesTransfers = $this->productRepository->getProductAbstractLocalizedAttributes($productAbstractIds);

            foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
                if (isset($productAbstractLocalizedAttributesTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()])) {
                    $productAbstractTransfer->setLocalizedAttributes(
                        new ArrayObject($productAbstractLocalizedAttributesTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()]),
                    );
                }
            }
        }

        foreach ($this->productAbstractCollectionExpanderPlugins as $productAbstractCollectionExpanderPlugin) {
            $productAbstractCollectionTransfer = $productAbstractCollectionExpanderPlugin->expand(
                $productAbstractCollectionTransfer,
                $productAbstractCriteriaTransfer,
            );
        }

        return $productAbstractCollectionTransfer;
    }
}
