<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\Model;

use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface;
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeWriter implements ProductAlternativeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface
     */
    protected $productAlternativeEntityManager;

    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface $productAlternativeEntityManager
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     */
    public function __construct(
        ProductAlternativeEntityManagerInterface $productAlternativeEntityManager,
        ProductAlternativeRepositoryInterface $productAlternativeRepository
    ) {
        $this->productAlternativeEntityManager = $productAlternativeEntityManager;
        $this->productAlternativeRepository = $productAlternativeRepository;
    }

    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Rewrite the logic
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): SpyProductAlternativeEntityTransfer
    {
        return new SpyProductAlternativeEntityTransfer();
    }

    /**
     * TODO: Replace transfer that is returned to proper one
     * TODO: Rewrite the logic
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer
     */
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): SpyProductAlternativeEntityTransfer
    {
        return new SpyProductAlternativeEntityTransfer();
    }

    // TODO: Add methods to get alternative product
}
