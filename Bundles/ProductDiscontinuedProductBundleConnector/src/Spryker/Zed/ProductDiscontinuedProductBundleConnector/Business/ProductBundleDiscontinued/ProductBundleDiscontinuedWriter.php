<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface;

class ProductBundleDiscontinuedWriter implements ProductBundleDiscontinuedWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface
     */
    protected $productDiscontinuedProductBundleConnectorRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface $productDiscontinuedProductBundleConnectorRepository
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     */
    public function __construct(
        ProductDiscontinuedProductBundleConnectorRepositoryInterface $productDiscontinuedProductBundleConnectorRepository,
        ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
    ) {
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->productDiscontinuedProductBundleConnectorRepository = $productDiscontinuedProductBundleConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function discontinueRelatedBundle(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        $relatedBundleProductsIds = $this->productDiscontinuedProductBundleConnectorRepository
            ->findRelatedBundleProductsIds($productDiscontinuedTransfer->getIdProductDiscontinued());

        if (count($relatedBundleProductsIds)) {
            foreach ($relatedBundleProductsIds as $idProduct) {
                $this->discontinueProduct($idProduct);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function discontinueProductBundleByBundledProducts(ProductConcreteTransfer $productConcreteTransfer): void
    {
        if (!$productConcreteTransfer->getProductBundle()) {
            return;
        }

        $productForBundleTransfers = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        if (!$productForBundleTransfers->count()) {
            return;
        }

        $productConcreteIds = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $productConcreteIds[] = $productForBundleTransfer->getIdProductConcrete();
        }

        $criteriaFilterTransfer = (new ProductDiscontinuedCriteriaFilterTransfer())
            ->setProductConcreteIds($productConcreteIds);

        $productDiscontinuedCollectionTransfer = $this->productDiscontinuedFacade
            ->findProductDiscontinuedByConcreteProductsCollection($criteriaFilterTransfer);

        if (!$productDiscontinuedCollectionTransfer->getDiscontinuedProducts()->count()) {
            return;
        }

        $this->discontinueProduct($productConcreteTransfer->getIdProductConcrete());
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    protected function discontinueProduct(int $idProduct): void
    {
        $productDiscontinuedRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($idProduct);

        $this->productDiscontinuedFacade->markProductAsDiscontinued($productDiscontinuedRequestTransfer);
    }
}
