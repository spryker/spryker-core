<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Persistence\ProductPackagingUnitPersistenceFactory getFactory()
 */
class ProductPackagingUnitEntityManager extends AbstractEntityManager implements ProductPackagingUnitEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function saveProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        $productPackagingUnitTypeEntity = $this->getFactory()
            ->createProductPackagingUnitTypeQuery()
            ->filterByIdProductPackagingUnitType($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType())
            ->findOneOrCreate();

        $productPackagingUnitTypeEntity->fromArray($productPackagingUnitTypeTransfer->modifiedToArray());
        $productPackagingUnitTypeEntity->save();

        $productPackagingUnitTypeTransfer->fromArray($productPackagingUnitTypeEntity->toArray(), true);

        return $productPackagingUnitTypeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        return $this->getFactory()
                ->createProductPackagingUnitTypeQuery()
                ->filterByIdProductPackagingUnitType($productPackagingUnitTypeTransfer->getIdProductPackagingUnitType())
                ->delete() > 0;
    }
}
