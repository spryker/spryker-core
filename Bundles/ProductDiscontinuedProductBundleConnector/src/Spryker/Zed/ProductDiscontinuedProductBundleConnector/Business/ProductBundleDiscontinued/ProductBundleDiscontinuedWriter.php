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
    public function discontinueBundleByProducts(ProductConcreteTransfer $productConcreteTransfer): void
    {
        if ($productConcreteTransfer->getProductBundle() === null) {
            return;
        }

        $bundledProducts = $productConcreteTransfer->getProductBundle()->getBundledProducts();

        if ($bundledProducts->count() == 0) {
            return;
        }

        $productDiscontinuedCriteriaFilterTransfer = new ProductDiscontinuedCriteriaFilterTransfer();

        foreach ($bundledProducts as $bundledProduct) {
            $productDiscontinuedResponseTransfer = $this->productDiscontinuedFacade
                ->findProductDiscontinuedByProductId($bundledProduct->getIdProductConcrete());

            if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
                $this->discontinueProduct($productConcreteTransfer->getIdProductConcrete());
                break;
            }
        }
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
