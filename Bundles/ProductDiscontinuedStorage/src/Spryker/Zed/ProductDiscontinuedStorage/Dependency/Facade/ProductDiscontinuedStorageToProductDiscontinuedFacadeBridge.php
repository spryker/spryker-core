<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;

class ProductDiscontinuedStorageToProductDiscontinuedFacadeBridge implements ProductDiscontinuedStorageToProductDiscontinuedFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface
     */
    protected $productDiscontinuedFacade;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface $productDiscontinuedFacade
     */
    public function __construct($productDiscontinuedFacade)
    {
        $this->productDiscontinuedFacade = $productDiscontinuedFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function getProductDiscontinuedCollection(
        ProductDiscontinuedCriteriaTransfer $productDiscontinuedCriteriaTransfer
    ): ProductDiscontinuedCollectionTransfer {
        return $this->productDiscontinuedFacade->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer);
    }
}
