<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnitLeadProduct;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface;

class ProductPackagingUnitLeadProductReader implements ProductPackagingUnitLeadProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitRepositoryInterface $repository
     */
    public function __construct(
        ProductPackagingUnitRepositoryInterface $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        return $this->repository->getProductPackagingLeadProductByIdProductAbstract($idProductAbstract);
    }
}
