<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock;

use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class CmsBlockConfig extends AbstractBundleConfig
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
        return $this->getAbsolutePath($templateRelativePath, 'Yves');
    }

    /**
     * @param string $templateRelativePath
     *
     * @return array
     */
    public function getTemplateRealPaths($templateRelativePath)
    {
        return [
            $this->getAbsolutePath($templateRelativePath, 'Shared'),
        ];
    }

    /**
     * @param string $templateRelativePath
     * @param string $twigLayer
     *
     * @return string
     */
    protected function getAbsolutePath($templateRelativePath, $twigLayer)
    {
        $templateRelativePath = str_replace(static::CMS_TWIG_TEMPLATE_PREFIX, '', $templateRelativePath);

        return sprintf(
            '%s/%s/%s/Cms/Theme/%s%s',
            APPLICATION_SOURCE_DIR,
            $this->get(CmsBlockConstants::PROJECT_NAMESPACE),
            $twigLayer,
            $this->get(CmsBlockConstants::YVES_THEME),
            $templateRelativePath
        );
    }

}
