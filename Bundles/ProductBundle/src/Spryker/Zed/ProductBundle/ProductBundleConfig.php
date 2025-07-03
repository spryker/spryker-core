<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductBundleConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\Availability\AvailabilityConfig::ERROR_TYPE_AVAILABILITY
     *
     * @var string
     */
    protected const ERROR_TYPE_AVAILABILITY = 'Availability';

    /**
     * @see \Spryker\Zed\Availability\AvailabilityConfig::PARAMETER_PRODUCT_SKU_AVAILABILITY
     *
     * @var string
     */
    protected const PARAMETER_PRODUCT_SKU_AVAILABILITY = '%sku%';

    /**
     * @var bool
     */
    protected const USE_BATCH_AVAILABILITY_CHECK = false;

    /**
     * @api
     *
     * @return string
     */
    public function getAvailabilityErrorType(): string
    {
        return static::ERROR_TYPE_AVAILABILITY;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAvailabilityProductSkuParameter(): string
    {
        return static::PARAMETER_PRODUCT_SKU_AVAILABILITY;
    }

    /**
     * Specification:
     * - Defines a list of allowed fields to be copied from a source bundle item to destination bundled items.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAllowedBundleItemFieldsToCopy(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Defines whether batch availability check facade method should be used.
     *
     * @api
     *
     * @return bool
     */
    public function useBatchAvailabilityCheck(): bool
    {
        return static::USE_BATCH_AVAILABILITY_CHECK;
    }
}
