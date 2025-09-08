<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchHttp;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SearchHttpConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const SOURCE_IDENTIFIER_PRODUCT = 'product';

    /**
     * @var string
     */
    public const TYPE_SEARCH_HTTP = 'TYPE_SEARCH_HTTP';

    /**
     * @var string
     */
    public const TYPE_SUGGESTION_SEARCH_HTTP = 'TYPE_SUGGESTION_SEARCH_HTTP';

    /**
     * @var string
     */
    public const TYPE_PRODUCT_CONCRETE_SEARCH_HTTP = 'TYPE_PRODUCT_CONCRETE_SEARCH_HTTP';

    /**
     * @var string
     */
    public const FACET_TYPE_PRICE_RANGE = 'price-range';

    /**
     * @var string
     */
    public const FACET_TYPE_CATEGORY = 'category';

    /**
     * @var string
     */
    public const FACET_TYPE_RANGE_VALUE_MIN = 'min';

    /**
     * @var string
     */
    public const FACET_TYPE_RANGE_VALUE_MAX = 'max';

    /**
     * @var string
     */
    public const SEARCH_HTTP_CONFIG_RESOURCE_NAME = 'search_http_config';

    /**
     * @var string
     */
    public const SEARCH_HTTP_CONFIG_SYNC_QUEUE = 'sync.http.search.config';

    /**
     * @var string
     */
    public const SEARCH_HTTP_METHOD = 'GET';

    /**
     * @uses \Spryker\Shared\Search\SearchConfig::FACET_TYPE_ENUMERATION
     *
     * @var string
     */
    public const FACET_TYPE_ENUMERATION = 'enumeration';

    /**
     * @uses \Spryker\Shared\Search\SearchConfig::FACET_TYPE_RANGE
     *
     * @var string
     */
    public const FACET_TYPE_RANGE = 'range';
}
