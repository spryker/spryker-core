<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued;

use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function discontinueProductBundleByBundledProducts(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $bundledProductConcreteIds = $this->getBundledProductConcreteIds($productConcreteTransfer);

        if (
            !$bundledProductConcreteIds
            || !$this->productDiscontinuedFacade->isAnyProductConcreteDiscontinued($bundledProductConcreteIds)
        ) {
            return $productConcreteTransfer;
        }

        $this->discontinueProduct($productConcreteTransfer->getIdProductConcrete());

        return $productConcreteTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array<int>
     */
    protected function getBundledProductConcreteIds(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $bundledProductConcreteIds = [];

        if (!$productConcreteTransfer->getProductBundle()) {
            return $bundledProductConcreteIds;
        }

        foreach ($productConcreteTransfer->getProductBundle()->getBundledProducts() as $productForBundleTransfer) {
            $bundledProductConcreteIds[] = $productForBundleTransfer->getIdProductConcrete();
        }

        return $bundledProductConcreteIds;
    }
}
