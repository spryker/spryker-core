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
     * @deprecated use getTemplateRealPaths() instead
     *
     * @param string $templateRelativePath
     *
     * @return string
     */
    public function getTemplateRealPath($templateRelativePath)
    {
        return $this->getPhysicalAddress($templateRelativePath, 'Yves');
    }

    /**
     * @param string $templateRelativePath
     *
     * @return array
     */
    public function getTemplateRealPaths($templateRelativePath)
    {
        return [
            $this->getPhysicalAddress($templateRelativePath, 'Yves'),
            $this->getPhysicalAddress($templateRelativePath, 'Shared'),
        ];
    }

    /**
     * @return bool
     */
    public function appendPrefixToCmsPageUrl()
    {
        return false;
    }

    /**
     * @param string $templateRelativePath
     * @param string $twigLayer
     *
     * @return string
     */
    protected function getPhysicalAddress($templateRelativePath, $twigLayer)
    {
        $templateRelativePath = str_replace(static::CMS_TWIG_TEMPLATE_PREFIX, '', $templateRelativePath);

        return sprintf('%s/%s/%s/Cms/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsConstants::PROJECT_NAMESPACE),
            $twigLayer,
            $this->get(CmsConstants::YVES_THEME),
            $templateRelativePath
        );
    }

}
