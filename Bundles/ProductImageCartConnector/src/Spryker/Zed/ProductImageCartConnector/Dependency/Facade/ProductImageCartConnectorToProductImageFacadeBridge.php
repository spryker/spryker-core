<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;

class ProductImageCartConnectorToProductImageFacadeBridge implements ProductImageCartConnectorToProductImageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface
     */
    protected $productImageFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface $productImageFacade
     */
    public function __construct($productImageFacade)
    {
        $this->productImageFacade = $productImageFacade;
    }

    /**
     *
     * @deprecated Use {@link \Spryker\Zed\ProductImageCartConnector\Dependency\Facade\ProductImageCartConnectorToProductImageFacadeBridge::getConcreteProductImageSetCollection()} instead.
     *
     * @param array<int> $productIds
     * @param string $productImageSetName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array
    {
        return $this->productImageFacade->getProductImagesByProductIdsAndProductImageSetName($productIds, $productImageSetName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getConcreteProductImageSetCollection(ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer): ProductImageSetCollectionTransfer
    {
        return $this->productImageFacade->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getAbstractProductImageSetCollection(ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer): ProductImageSetCollectionTransfer
    {
        return $this->productImageFacade->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);
    }
}
