<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SearchConfig extends AbstractSharedConfig
{
    /**
     * Available facet types
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';
    public const FACET_TYPE_RANGE = 'range';
    public const FACET_TYPE_PRICE_RANGE = 'price-range';
    public const FACET_TYPE_CATEGORY = 'category';

    /**
     * @return array
     */
    public function getIndexNameNap(): array
    {
        return $this->get(SearchConstants::INDEX_NAME_MAP, []);
    }
}
