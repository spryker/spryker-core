<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer;

class ProductAlternativeStorageToProductAlternativeFacadeBridge implements ProductAlternativeStorageToProductAlternativeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface $productAlternativeFacade
     */
    public function __construct($productAlternativeFacade)
    {
        $this->productAlternativeFacade = $productAlternativeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativeCollection(
        ProductAlternativeCriteriaTransfer $productAlternativeCriteriaTransfer
    ): ProductAlternativeCollectionTransfer {
        return $this->productAlternativeFacade->getProductAlternativeCollection($productAlternativeCriteriaTransfer);
    }
}
