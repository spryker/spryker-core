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
    const FACET_TYPE_ENUMERATION = 'enumeration';
    const FACET_TYPE_RANGE = 'range';
    const FACET_TYPE_PRICE_RANGE = 'price-range';
    const FACET_TYPE_CATEGORY = 'category';
}
