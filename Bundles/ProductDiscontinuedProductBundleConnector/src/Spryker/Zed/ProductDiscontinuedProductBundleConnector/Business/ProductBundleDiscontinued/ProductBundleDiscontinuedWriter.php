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
     * @return void
     */
    public function discontinueProductBundleByBundledProducts(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $bundledProductConcreteIds = $this->getBundledProductConcreteIds($productConcreteTransfer);

        if (!$bundledProductConcreteIds
            || !$this->productDiscontinuedFacade->isAnyProductConcreteDiscontinued($bundledProductConcreteIds)
        ) {
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

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int[]
     */
    protected function getBundledProductConcreteIds(ProductConcreteTransfer $productConcreteTransfer): array
    {
        $bundledProductConcreteIds = [];

        if (!$productConcreteTransfer->getProductBundle()) {
            return $bundledProductConcreteIds;
        }

        $productForBundleTransfers = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        if (!$productForBundleTransfers->count()) {
            return $bundledProductConcreteIds;
        }

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            $bundledProductConcreteIds[] = $productForBundleTransfer->getIdProductConcrete();
        }

        return $bundledProductConcreteIds;
    }
}
