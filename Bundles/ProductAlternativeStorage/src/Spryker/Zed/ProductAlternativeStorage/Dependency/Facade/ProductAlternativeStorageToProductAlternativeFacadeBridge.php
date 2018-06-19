<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

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
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function findProductAlternativeTransfer($idProduct): ProductAlternativeTransfer
    {
        return $this->productAlternativeFacade->getProductAlternativeByIdProductAlternative($idProduct);
    }
}
