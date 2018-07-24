<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductPackagingUnitConfig extends AbstractBundleConfig
{
    /**
     * @const int|null Controls the threshold for quantity which above the quantity should not be splitted. Null value inactivates the threshold.
     */
    protected const ITEM_NONSPLIT_QUANTITY_THRESHOLD = null;

    /**
     * Default packaging unit type name.
     */
    protected const DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME = 'packaging_unit_type.item.name';

    /**
     * Infrastructural packaging unit types.
     */
    protected const INFRASTRUCTURAL_PACKAGING_UNIT_TYPES = [
        [
            'name' => 'packaging_unit_type.item.name',
        ],
    ];

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer[]
     */
    public function getInfrastructuralPackagingUnitTypes(): array
    {
        $infrastructuralPackagingUnitTypes = [];
        foreach (static::INFRASTRUCTURAL_PACKAGING_UNIT_TYPES as $infrastructuralPackagingUnitType) {
            $infrastructuralPackagingUnitTypes[] = (new ProductPackagingUnitTypeTransfer())->fromArray($infrastructuralPackagingUnitType);
        }

        return $infrastructuralPackagingUnitTypes;
    }

    /**
     * @return string[]
     */
    public function getInfrastructuralProductPackagingUnitTypeNames(): array
    {
        return array_column(static::INFRASTRUCTURAL_PACKAGING_UNIT_TYPES, 'name');
    }

    /**
     * @return string
     */
    public function getDefaultProductPackagingUnitTypeName(): string
    {
        return static::DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME;
    }

    /**
     * @return int|null
     */
    public function findItemQuantityThreshold(): ?int
    {
        return static::ITEM_NONSPLIT_QUANTITY_THRESHOLD;
    }
}
