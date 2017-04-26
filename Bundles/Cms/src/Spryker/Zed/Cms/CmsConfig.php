<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms;

use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsConfig extends AbstractBundleConfig
{

    const CMS_TWIG_TEMPLATE_PREFIX = '@Cms';

    /**
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        $templateRelativePath = str_replace(static::CMS_TWIG_TEMPLATE_PREFIX, '', $templateRelativePath);
        $physicalAddress = sprintf(
            '%s/%s/Shared/Cms/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsConstants::PROJECT_NAMESPACE),
            $this->get(CmsConstants::YVES_THEME),
            $templateRelativePath
        );

        return $physicalAddress;
    }

    /**
     * @return bool
     */
    public function appendPrefixToCmsPageUrl()
    {
        return false;
    }

}
