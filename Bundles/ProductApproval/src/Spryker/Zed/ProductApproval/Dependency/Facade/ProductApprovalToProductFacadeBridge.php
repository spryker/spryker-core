<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductApprovalToProductFacadeBridge implements ProductApprovalToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        return $this->productFacade->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array
    {
        return $this->productFacade->getProductConcreteTransfersByProductIds($productIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract): ?ProductAbstractTransfer
    {
        return $this->productFacade->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param string $concreteSku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku): int
    {
        return $this->productFacade->getProductAbstractIdByConcreteSku($concreteSku);
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array
    {
        return $this->productFacade->getRawProductConcreteTransfersByConcreteSkus($productConcreteSkus);
    }
}
