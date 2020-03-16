<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence\Propel;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface;

class ProductConcreteMapper
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Service\ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductOfferGuiPageToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $productConcreteEntities
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function mapProductConcreteEntitiesToProductTableDataTransfer(
        Collection $productConcreteEntities,
        ProductTableDataTransfer $productTableDataTransfer
    ): ProductTableDataTransfer {
        foreach ($productConcreteEntities as $productConcreteEntity) {
            $productConcreteTransfer = $this->mapProductConcreteEntityToProductConcreteTransfer(
                $productConcreteEntity,
                new ProductConcreteTransfer()
            );

            $productTableDataTransfer->addProductConcrete($productConcreteTransfer);
        }

        return $productTableDataTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductConcreteEntityToProductConcreteTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $attributes = $this->utilEncodingService->decodeJson($productEntity->getAttributes(), true);

        return $productConcreteTransfer
            ->fromArray($productEntity->toArray(), true)
            ->setAttributes(is_array($attributes) ? $attributes : [])
            ->setIdProductConcrete($productEntity->getIdProduct());
    }
}
