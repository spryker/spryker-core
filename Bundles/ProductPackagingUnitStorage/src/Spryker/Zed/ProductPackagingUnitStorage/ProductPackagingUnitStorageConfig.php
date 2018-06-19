<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductPackagingUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * default_amount value for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_VALUE = 1;

    /**
     * is_variable value for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_IS_VARIABLE_VALUE = false;

    /**
     * amount_min value for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_MIN = 1;

    /**
     * amount_interval value for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_INTERVAL = 1;

    /**
     * default name value for packaging unit storage values.
     */
    protected const PRODUCT_ABSTRACT_STORAGE_DEFAULT_NAME = 'packaging_unit_type.item.name';

    /**
     * @return int
     */
    public function getProductAbstractStorageDefaultAmountValue(): int
    {
        return static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_VALUE;
    }

    /**
     * @return bool
     */
    public function isProductAbstractStorageDefaultIsVariableValue(): bool
    {
        return static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_IS_VARIABLE_VALUE;
    }

    /**
     * @return int
     */
    public function getProductAbstractStorageDefaultAmountMin(): int
    {
        return static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_MIN;
    }

    /**
     * @return int
     */
    public function getProductAbstractStorageDefaultAmountInterval(): int
    {
        return static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_AMOUNT_INTERVAL;
    }

    /**
     * @return string
     */
    public function getProductAbstractStorageDefaultName(): string
    {
        return static::PRODUCT_ABSTRACT_STORAGE_DEFAULT_NAME;
    }
}
