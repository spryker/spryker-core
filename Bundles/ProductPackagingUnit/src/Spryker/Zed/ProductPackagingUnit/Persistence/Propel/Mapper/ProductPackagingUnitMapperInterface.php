<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProduct;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType;

interface ProductPackagingUnitMapperInterface
{
    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnit $productPackagingUnitEntity
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTransfer $productPackagingUnitTransfer
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null $productPackagingLeadProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer
     */
    public function mapProductPackagingUnitTransfer(
        SpyProductPackagingUnit $productPackagingUnitEntity,
        ProductPackagingUnitTransfer $productPackagingUnitTransfer,
        ?ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer = null
    ): ProductPackagingUnitTransfer;

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitType $spyProductPackagingUnitType
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function mapProductPackagingUnitTypeTransfer(
        SpyProductPackagingUnitType $spyProductPackagingUnitType,
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * @param \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProduct $productPackagingLeadProductEntity
     * @param \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer
     */
    public function mapProductPackagingLeadProductTransfer(
        SpyProductPackagingLeadProduct $productPackagingLeadProductEntity,
        ProductPackagingLeadProductTransfer $productPackagingLeadProductTransfer
    ): ProductPackagingLeadProductTransfer;
}
