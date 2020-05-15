<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\GuiTableDataRequest;

/**
 * Provides capabilities to normalize data for filters.
 *
 * Use this plugin when a filter is type-sensitive or uses complex data structure.
 *
 * @todo move to Dependency/Plugin when GuiTable is extracted out of ProductOfferMerchantPortalGui
 */
interface FilterValueNormalizerPluginInterface
{
    /**
     * @param string $filterType
     *
     * @return bool
     */
    public function isApplicable(string $filterType): bool;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function normalizeValue($value);
}
