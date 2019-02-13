<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductBundleDiscontinued;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface;

class ProductBundleDiscontinuedReader implements ProductBundleDiscontinuedReaderInterface
{
    protected const ERROR_MESSAGE_PRODUCT_BUNDLE_DISCONTINUED = 'You can not unmark the discontinued bundle until any of the bundled products are discontinued.';

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface
     */
    protected $productDiscontinuedProductBundleConnectorRepository;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @var \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Persistence\ProductDiscontinuedProductBundleConnectorRepositoryInterface $productDiscontinuedProductBundleConnectorRepository
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     * @param \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade\ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface $productBundleFacade
     */
    public function __construct(
        ProductDiscontinuedProductBundleConnectorRepositoryInterface $productDiscontinuedProductBundleConnectorRepository,
        ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface $productDiscontinuedFacade,
        ProductDiscontinuedProductBundleConnectorToProductBundleFacadeInterface $productBundleFacade
    ) {
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
        $this->productBundleFacade = $productBundleFacade;
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
        $bundledProductConcreteIds = $this->getBundledProductConcreteIds($productDiscontinuedTransfer);

        if (!$bundledProductConcreteIds
            || !$this->productDiscontinuedFacade->isOneOfConcreteProductsDiscontinued($bundledProductConcreteIds)
        ) {
            return $productDiscontinuedResponseTransfer;
        }

        $errorMessageTransfer = (new MessageTransfer())->setValue(static::ERROR_MESSAGE_PRODUCT_BUNDLE_DISCONTINUED);

        return $productDiscontinuedResponseTransfer->setIsSuccessful(false)
            ->addMessage($errorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return int[]
     */
    protected function getBundledProductConcreteIds(ProductDiscontinuedTransfer $productDiscontinuedTransfer): array
    {
        $bundledProductConcreteIds = [];
        $bundledProducts = $this->productBundleFacade->findBundledProductsByIdProductConcrete(
            $productDiscontinuedTransfer->getFkProduct()
        );

        if (!$bundledProducts->count()) {
            return $bundledProductConcreteIds;
        }

        foreach ($bundledProducts as $bundledProduct) {
            $bundledProductConcreteIds[] = $bundledProduct->getIdProductConcrete();
        }

        return $bundledProductConcreteIds;
    }
}
