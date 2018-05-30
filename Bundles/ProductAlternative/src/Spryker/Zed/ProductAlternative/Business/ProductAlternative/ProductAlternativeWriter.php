<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeResponseTransfer;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface;

class ProductAlternativeWriter implements ProductAlternativeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface
     */
    protected $productAlternativeEntityManager;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
     */
    public function __construct(
        ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
    ) {
        $this->productAlternativeEntityManager = $productAlternativeEntityManager;
    }

    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeResponseTransfer
    {
        $productAbstractAlternativeTransfer = $this->productAlternativeEntityManager->createProductAbstractAlternative($idProduct, $idProductAbstractAlternative);

        return (new ProductAlternativeResponseTransfer())
            ->setProductAlternative($productAbstractAlternativeTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeResponseTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeResponseTransfer
    {
        $productConcreteAlternativeTransfer = $this->productAlternativeEntityManager->createProductConcreteAlternative($idProduct, $idProductConcreteAlternative);

        return (new ProductAlternativeResponseTransfer())
            ->setProductAlternative($productConcreteAlternativeTransfer)
            ->setIsSuccessful(true);
    }
}
