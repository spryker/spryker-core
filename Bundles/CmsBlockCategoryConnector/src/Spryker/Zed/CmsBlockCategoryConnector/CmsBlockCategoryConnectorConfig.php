<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockCategoryConnectorConfig extends AbstractBundleConfig
{
    /**
     * CMS Block on a category page only
     */
    const CATEGORY_TEMPLATE_CMS_BLOCK = 'CMS Block';

    /**
     * Category with CMS Block together
     */
    const CATEGORY_TEMPLATE_CATEGORY_CMS_BLOCK = 'Category + CMS Block';

    /**
     * Position at the top of a category page
     */
    const CMS_BLOCK_CATEGORY_POSITION_TOP = 'Top';

    /**
     * Position at the middle of a category page
     */
    const CMS_BLOCK_CATEGORY_POSITION_MIDDLE = 'Middle';

    /**
     * Position at the bottom of a category page
     */
    const CMS_BLOCK_CATEGORY_POSITION_BOTTOM = 'Bottom';

}
