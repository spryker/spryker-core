<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearchExtension\Dependency\Plugin;

interface MerchantSearchDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Allows to expand merchant search data before saving to Elastica.
     *
     * @api
     *
     * @param mixed[] $merchantSearchData
     *
     * @return mixed[]
     */
    public function expand(array $merchantSearchData): array;
}
