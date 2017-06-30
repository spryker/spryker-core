<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsBlockCategoryConnector;

use Spryker\Shared\CmsBlock\CmsBlockConfig;

interface CmsBlockCategoryConnectorConstants
{

    /**
     * Specification:
     * - Used to define a storage key
     * - Used in Yves to provide an option name for client
     *
     * @api
     */
    const OPTION_NAME = 'category';

    /**
     * Specification:
     * - Full name for storage key
     *
     * @api
     */
    const RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR = CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK . '.' . self::OPTION_NAME;

    /**
     * Specification:
     * - Available positions of blocks on a category page
     *
     * @api
     */
    const CMS_BLOCK_CATEGORY_POSITION_LIST = 'CMS_BLOCK_CATEGORY_POSITION_LIST';

}
