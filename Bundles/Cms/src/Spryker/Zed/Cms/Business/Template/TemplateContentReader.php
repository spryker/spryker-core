<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use InvalidArgumentException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\Cms\CmsConfig;
use Symfony\Component\Finder\Finder;

class TemplateContentReader implements TemplateContentReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(CmsConfig $cmsConfig, Finder $finder)
    {
        $this->cmsConfig = $cmsConfig;
        $this->finder = $finder;
    }

    /**
     * @param string $templatePath
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException
     *
     * @return string
     */
    public function getTemplateContent(string $templatePath): string
    {
        $fileName = pathinfo($templatePath, PATHINFO_BASENAME);
        $templateFilePaths = $this->cmsConfig->getTemplateRealPaths($templatePath);

        foreach ($templateFilePaths as $templateFile) {
            try {
                $this->finder
                    ->files()
                    ->name($fileName)
                    ->in(pathinfo($templateFile, PATHINFO_DIRNAME));
            } catch (InvalidArgumentException $exception) {
                continue;
            }

            foreach ($this->finder as $file) {
                return $file->getContents();
            }
        }

        throw new TemplateFileNotFoundException('');
    }
}
