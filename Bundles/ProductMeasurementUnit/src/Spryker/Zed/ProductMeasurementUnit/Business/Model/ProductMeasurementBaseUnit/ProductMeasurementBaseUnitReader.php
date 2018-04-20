<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class ProductMeasurementBaseUnitReader implements ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     */
    public function __construct(ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository)
    {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransferByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer
    {
        return $this->productMeasurementUnitRepository->getProductMeasurementBaseUnitTransferByIdProduct($idProduct);
    }
}
