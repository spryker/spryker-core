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
     * Default position
     */
    public const CMS_BLOCK_CATEGORY_POSITION_DEFAULT = '';

    /**
     * A name of category template: Category and CmsBlock contents are together
     */
    public const CATEGORY_TEMPLATE_WITH_CMS_BLOCK = 'Catalog + CMS Block';

    /**
     * A name of category template: CmsBlock content is presented alone
     */
    public const CATEGORY_TEMPLATE_ONLY_CMS_BLOCK = 'CMS Block';

    /**
     * @return array
     */
    public function getCmsBlockCategoryPositionList()
    {
        return [
            static::CMS_BLOCK_CATEGORY_POSITION_DEFAULT,
        ];
    }

    /**
     * @return string
     */
    public function getCmsBlockCategoryPositionDefault()
    {
        $list = $this->getCmsBlockCategoryPositionList();

        return array_shift($list);
    }
}
