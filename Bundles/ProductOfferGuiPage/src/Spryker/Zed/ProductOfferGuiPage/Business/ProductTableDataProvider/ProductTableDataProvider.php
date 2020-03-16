<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductTableDataProvider implements ProductTableDataProviderInterface
{
    protected const EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR = ', ';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface
     */
    protected $productOfferGuiPageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface[]
     */
    protected $productTableDataHydrators;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository
     * @param \Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator\ProductTableDataHydratorInterface[] $productTableDataHydrators
     */
    public function __construct(
        ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository,
        array $productTableDataHydrators
    ) {
        $this->productOfferGuiPageRepository = $productOfferGuiPageRepository;
        $this->productTableDataHydrators = $productTableDataHydrators;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): ProductTableDataTransfer
    {
        $productTableDataTransfer = $this->productOfferGuiPageRepository
            ->getProductTableData($productTableCriteriaTransfer);
        $productTableDataTransfer = $this->transformProductConcreteNames($productTableDataTransfer);
        $productTableDataTransfer = $this->hydrateProductTableData($productTableCriteriaTransfer, $productTableDataTransfer);

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    protected function transformProductConcreteNames(ProductTableDataTransfer $productTableDataTransfer): ProductTableDataTransfer
    {
        foreach ($productTableDataTransfer->getConcreteProducts() as $productConcreteTransfer) {
            $extendedProductConcreteName = $this->buildExtendedProductConcreteName($productConcreteTransfer);
            $productConcreteTransfer->setName($extendedProductConcreteName);
        }

        return $productTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function buildExtendedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        $productConcreteName = $productConcreteTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];

        foreach ($productConcreteTransfer->getAttributes() as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        return implode(static::EXTENDED_PRODUCT_CONCRETE_NAME_SEPARATOR, $extendedProductConcreteNameParts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    protected function hydrateProductTableData(
        ProductTableCriteriaTransfer $productTableCriteriaTransfer,
        ProductTableDataTransfer $productTableDataTransfer
    ): ProductTableDataTransfer {
        foreach ($this->productTableDataHydrators as $productTableDataHydrator) {
            $productTableDataTransfer = $productTableDataHydrator->hydrateProductTableData(
                $productTableDataTransfer,
                $productTableCriteriaTransfer
            );
        }

        return $productTableDataTransfer;
    }
}
