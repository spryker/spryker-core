<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Spryker\Zed\Cms\CmsConfig;

class TemplatePlaceholderParser implements TemplatePlaceholderParserInterface
{
    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(CmsConfig $cmsConfig)
    {
        $this->cmsConfig = $cmsConfig;
    }

    /**
     * @param string $templateContent
     *
     * @return string[]
     */
    public function getTemplatePlaceholders(string $templateContent): array
    {
        preg_match_all($this->cmsConfig->getPlaceholderPattern(), $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine[0]) === 0) {
            return [];
        }

        preg_match_all($this->cmsConfig->getPlaceholderValuePattern(), implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

        return $placeholderMap[1];
    }
}
