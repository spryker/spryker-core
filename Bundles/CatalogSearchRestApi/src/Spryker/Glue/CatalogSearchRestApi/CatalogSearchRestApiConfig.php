<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class CatalogSearchRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_CATALOG_SEARCH = 'catalog-search';
    /**
     * @var string
     */
    public const RESOURCE_CATALOG_SEARCH_SUGGESTIONS = 'catalog-search-suggestions';

    /**
     * @var string
     */
    public const QUERY_STRING_PARAMETER = 'q';

    /**
     * @var string
     */
    public const RESPONSE_CODE_PARAMETER_MUST_BE_INTEGER = '503';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_PARAMETER_MUST_BE_INTEGER = 'Value of %s must be of type integer.';

    /**
     * @api
     *
     * @return string[]
     */
    public function getIntegerRequestParameterNames(): array
    {
        return [
            'rating.min',
            'rating.max',
            'page.limit',
            'page.offset',
            'category',
        ];
    }
}
