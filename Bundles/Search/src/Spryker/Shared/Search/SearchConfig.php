<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

interface SearchConfig
{
    /**
     * Available facet types
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';
    public const FACET_TYPE_RANGE = 'range';
    public const FACET_TYPE_PRICE_RANGE = 'price-range';
    public const FACET_TYPE_CATEGORY = 'category';
}
