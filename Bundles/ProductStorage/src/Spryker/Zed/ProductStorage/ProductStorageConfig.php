<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductStorageConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductAbstractSynchronizationPoolName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcreteEventQueueName(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductAbstractEventQueueName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     *  - Determines whether to include the single-valued product super attributes into a product map.
     *
     * @api
     *
     * @return bool
     */
    public function isProductAttributesWithSingleValueIncluded(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - Determines if an attribute map of the abstract product will be generated using an optimized approach by filling up ProductAbstractStorage.attributeMap.attributeVariantMap.
     * - Otherwise will be used an old approach by filling up ProductAbstractStorage.attributeMap.attributeVariants.
     *
     * @api
     *
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @return bool
     */
    public function isOptimizedAttributeVariantsMapEnabled(): bool
    {
        return true;
    }
}
