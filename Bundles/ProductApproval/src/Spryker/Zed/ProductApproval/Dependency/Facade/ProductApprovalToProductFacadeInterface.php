<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Dependency\Facade;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductApprovalToProductFacadeInterface
{
    /**
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract): ?ProductAbstractTransfer;

    /**
     * @param string $concreteSku
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku): int;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array;
}
