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

    /**
     * A name of category template: Category and CmsBlock contents are together
     */
    const CATEGORY_TEMPLATE_WITH_CMS_BLOCK = 'Category + CMS Block';

    /**
     * A name of category template: CmsBlock content is presented alone
     */
    const CATEGORY_TEMPLATE_ONLY_CMS_BLOCK = 'CMS Block';
}
