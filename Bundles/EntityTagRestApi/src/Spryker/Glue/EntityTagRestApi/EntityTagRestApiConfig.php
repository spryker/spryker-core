<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class EntityTagRestApiConfig extends AbstractBundleConfig
{
    protected const RESOURCES_ENTITY_TAG_REQUIRED = [];

    /**
     * @return string[]
     */
    public function getEntityTagRequiredResources(): array
    {
        return static::RESOURCES_ENTITY_TAG_REQUIRED;
    }
}
