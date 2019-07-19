<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SearchElasticsearchConfig extends AbstractSharedConfig
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
    public function getIndexNameMap(): array
    {
        return $this->get(SearchElasticsearchConstants::INDEX_NAME_MAP, []);
    }
}
