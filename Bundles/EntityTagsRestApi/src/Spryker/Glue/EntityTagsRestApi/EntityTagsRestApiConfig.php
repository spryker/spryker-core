<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class EntityTagsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESPONSE_CODE_IF_MATCH_HEADER_MISSING = '005';
    /**
     * @var string
     */
    public const RESPONSE_CODE_IF_MATCH_HEADER_INVALID = '006';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_IF_MATCH_HEADER_MISSING = 'If-Match header is missing.';
    /**
     * @var string
     */
    public const RESPONSE_DETAIL_IF_MATCH_HEADER_INVALID = 'If-Match header value is invalid.';

    /**
     * @api
     *
     * @return string[]
     */
    public function getEntityTagRequiredResources(): array
    {
        return [];
    }
}
