<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\Cms\CmsConfig;

class TemplateReader implements TemplateReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(
        CmsConfig $cmsConfig
    ) {
        $this->cmsConfig = $cmsConfig;
    }

    /**
     * @param string $templatePath
     *
     * @return array
     */
    public function getPlaceholdersByTemplatePath(string $templatePath): array
    {
        $templateFiles = $this->cmsConfig->getTemplateRealPaths($templatePath);

        $placeholders = [];
        foreach ($templateFiles as $templateFile) {
            if (!$this->fileExists($templateFile)) {
                continue;
            }

            $placeholders = $this->getTemplatePlaceholders($templateFile);
        }

        return $placeholders;
    }

    /**
     * @param string $templateFile
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException
     *
     * @return array
     */
    protected function getTemplatePlaceholders(string $templateFile): array
    {
        if (!$this->fileExists($templateFile)) {
            throw new TemplateFileNotFoundException(
                sprintf('Template file not found in "%s"', $templateFile)
            );
        }

        $templateContent = $this->readTemplateContents($templateFile);

        preg_match_all($this->cmsConfig->getPlaceholderPattern(), $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine) === 0) {
            throw new MissingPlaceholdersException(
                sprintf(
                    'No placeholders found in "%s" template.',
                    $templateFile
                )
            );
        }

        preg_match_all($this->cmsConfig->getPlaceholderValuePattern(), implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

        return $placeholderMap[1];
    }

    /**
     * @param string $templateFile
     *
     * @return string
     */
    protected function readTemplateContents(string $templateFile): string
    {
        return file_get_contents($templateFile);
    }

    /**
     * @param string $templateFile
     *
     * @return bool
     */
    protected function fileExists(string $templateFile): bool
    {
        return file_exists($templateFile);
    }
}
