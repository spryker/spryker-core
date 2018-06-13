<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface;

class ProductBundleDiscontinuedWriter implements ProductBundleDiscontinuedWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface
     */
    protected $productBundleConnectorRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * ProductBundleDiscontinuedWriter constructor.
     *
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface $productBundleConnectorRepository
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     */
    public function __construct(
        ProductDiscontinuedProductBundleConnectorRepositoryInterface $productBundleConnectorRepository,
        ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
    ) {
        $this->productBundleConnectorRepository = $productBundleConnectorRepository;
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function discontinueRelatedBundle(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        $relatedBundleProductsIds = $this->productBundleConnectorRepository
            ->findRelatedBundleProductsIds($productDiscontinuedTransfer->getIdProductDiscontinued());

        if (count($relatedBundleProductsIds)) {
            foreach ($relatedBundleProductsIds as $idProduct) {
                $this->discontinueProduct($idProduct);
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
        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($idProduct);

        $this->productDiscontinuedFacade->markProductAsDiscontinued($productDiscontinuedRequestTransfer);
    }
}
