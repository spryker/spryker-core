<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlockCategoryConnector;

use Spryker\Shared\CmsBlock\CmsBlockConfig;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class CmsBlockCategoryConnectorConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Used to define a storage key
     * - Used in Yves to provide an option name for client
     */
    public const OPTION_NAME = 'category';

    /**
     * Specification:
     * - Used to define a storage key
     * - Used in Yves to provide an option name for client
     */
    public const OPTION_POSITION_NAME = 'category_position';

    /**
     * Specification:
     * - Full name for storage key for CMS Block to Category relation
     */
    public const RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR = CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK . '.' . self::OPTION_NAME;
}
