<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryExporter\Code\KeyBuilder;

use Spryker\Shared\CategoryExporter\CategoryExporterConstants;
use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;

abstract class SharedCategoryResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryExporterConstants::RESOURCE_TYPE_CATEGORY_NODE;
    }
}
