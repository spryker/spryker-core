<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class EntityTagsRestApiConfig extends AbstractBundleConfig
{
    protected const RESOURCES_ENTITY_TAG_REQUIRED = [];

    public const RESPONSE_CODE_PRECONDITION_REQUIRED = '005';
    public const RESPONSE_CODE_PRECONDITION_FAILED = '006';

    public const RESPONSE_DETAIL_PRECONDITION_REQUIRED = 'Precondition required';
    public const RESPONSE_DETAIL_PRECONDITION_FAILED = 'Precondition failed';

    /**
     * @return string[]
     */
    public function getEntityTagRequiredResources(): array
    {
        return static::RESOURCES_ENTITY_TAG_REQUIRED;
    }
}
