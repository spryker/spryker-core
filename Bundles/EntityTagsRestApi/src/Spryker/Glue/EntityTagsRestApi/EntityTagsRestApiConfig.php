<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class EntityTagsRestApiConfig extends AbstractBundleConfig
{
    public const RESPONSE_CODE_IF_MATCH_HEADER_MISSING = '005';
    public const RESPONSE_CODE_IF_MATCH_HEADER_INVALID = '006';

    public const RESPONSE_DETAIL_IF_MATCH_HEADER_MISSING = 'If-Match header is missing.';
    public const RESPONSE_DETAIL_IF_MATCH_HEADER_INVALID = 'If-Match header value is invalid.';

    /**
     * @return string[]
     */
    public function getEntityTagRequiredResources(): array
    {
        return [];
    }
}
