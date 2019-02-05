<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface;

class ProductBundleDiscontinuedReader implements ProductBundleDiscontinuedReaderInterface
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
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function checkBundledProducts(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedResponseTransfer
    {
        $productDiscontinuedResponseTransfer = (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(true);
        $productConcreteIds = $this->productDiscontinuedProductBundleConnectorRepository->getBundledProductsByProductDiscontinuedId(
            $productDiscontinuedTransfer->getIdProductDiscontinued()
        );

        if ($productConcreteIds[0] === null) {
            return $productDiscontinuedResponseTransfer;
        }

        foreach ($productConcreteIds as $productConcreteId) {
            $productDiscontinuedResponseTransfer = $this->productDiscontinuedFacade
                ->findProductDiscontinuedByProductId($productConcreteId);

            if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
                return $productDiscontinuedResponseTransfer->setProductDiscontinued(null)
                    ->setIsSuccessful(false);
            }
        }

        return (new ProductDiscontinuedResponseTransfer())->setIsSuccessful(true);
    }
}
