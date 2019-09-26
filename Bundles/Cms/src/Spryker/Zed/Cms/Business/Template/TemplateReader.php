<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Spryker\Zed\Cms\CmsConfig;
use Symfony\Component\Finder\SplFileInfo;

class TemplateReader implements TemplateReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface
     */
    protected $templatePlaceholderParser;

    /**
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     * @param \Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface $templatePlaceholderParser
     */
    public function __construct(
        CmsConfig $cmsConfig,
        TemplatePlaceholderParserInterface $templatePlaceholderParser
    ) {
        $this->cmsConfig = $cmsConfig;
        $this->templatePlaceholderParser = $templatePlaceholderParser;
    }

    /**
     * @param string $templatePath
     *
     * @return string[]
     */
    public function getPlaceholdersByTemplatePath(string $templatePath): array
    {
        $templateFilePaths = $this->cmsConfig->getTemplateRealPaths($templatePath);

        foreach ($templateFilePaths as $templateFile) {
            $fileInfo = new SplFileInfo($templateFile, '', '');
            if (!$fileInfo->isFile()) {
                continue;
            }

            return $this->templatePlaceholderParser->getTemplatePlaceholders($fileInfo->getContents());
        }

        return [];
    }
}
