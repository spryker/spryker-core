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
     * Default packaging unit type name.
     *
     * @var string
     */
    protected const DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME = 'packaging_unit_type.item.name';

    /**
     * Infrastructural packaging unit types.
     *
     * @var array
     */
    protected const INFRASTRUCTURAL_PACKAGING_UNIT_TYPES = [
        [
            'name' => 'packaging_unit_type.item.name',
        ],
    ];

    /**
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer>
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
     * @api
     *
     * @return array<string>
     */
    public function getInfrastructuralProductPackagingUnitTypeNames(): array
    {
        return array_column(static::INFRASTRUCTURAL_PACKAGING_UNIT_TYPES, 'name');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultProductPackagingUnitTypeName(): string
    {
        return static::DEFAULT_PRODUCT_PACKAGING_UNIT_TYPE_NAME;
    }
}
