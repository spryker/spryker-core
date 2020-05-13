<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductRelationGuiConfig extends AbstractBundleConfig
{
    protected const USE_OPTIMIZED_PRODUCT_TABLE = true;

    /**
     * @api
     *
     * @return string
     */
    public function getYvesHost(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }

    /**
     * Specification:
     * - If true, Spryker\Zed\ProductRelationGui\Communication\Table\ProductAbstractTable will be used.
     * - If false, Spryker\Zed\ProductRelationGui\Communication\Table\ProductTable will be used.
     *
     * @api
     *
     * @return bool
     */
    public function useOptimizedProductTable(): bool
    {
        return static::USE_OPTIMIZED_PRODUCT_TABLE;
    }
}
