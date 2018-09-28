<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockGuiConfig extends AbstractBundleConfig
{
    public const CMS_BLOCK_TEMPLATE_PATH = '@CmsBlock/template/';

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return static::CMS_BLOCK_TEMPLATE_PATH;
    }
}
