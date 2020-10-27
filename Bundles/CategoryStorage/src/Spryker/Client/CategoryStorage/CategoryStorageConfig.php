<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class CategoryStorageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\Catalog\Plugin\ConfigTransferBuilder\CategoryFacetConfigTransferBuilderPlugin::NAME
     */
    protected const CATEGORY_NAME = 'category';

    /**
     * @uses \Generated\Shared\Search\PageIndexMap::CATEGORY_ALL_PARENTS
     */
    protected const CATEGORY_ALL_PARENTS = 'category.all-parents';

    /**
     * To be able to work with data exported with collectors to redis, we need to bring this module into compatibility
     * mode. If this is turned on the CategoryExporterClient will be used instead.
     *
     * @api
     *
     * @return bool
     */
    public static function isCollectorCompatibilityMode(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns category facet aggregation name.
     *
     * @api
     *
     * @return string
     */
    public function getCategoryFacetAggregationName(): string
    {
        return sprintf('%s.%s', static::CATEGORY_ALL_PARENTS, static::CATEGORY_NAME);
    }
}
