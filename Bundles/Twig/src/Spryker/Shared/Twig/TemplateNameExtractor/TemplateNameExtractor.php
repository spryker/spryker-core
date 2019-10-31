<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\TemplateNameExtractor;

use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface;

class TemplateNameExtractor implements TemplateNameExtractorInterface
{
    /**
     * @var \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface $utilTextService
     */
    public function __construct(TwigToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function extractBundleName($name)
    {
        $nameWithoutPrefix = $this->getNameWithoutPrefix($name);
        $firstSeparatorPosition = $this->getFirstSeparatorPosition($nameWithoutPrefix);
        $bundleName = substr($nameWithoutPrefix, 0, $firstSeparatorPosition);

        return $this->filterBundleName($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return string
     */
    protected function filterBundleName($bundleName)
    {
        return ucfirst($this->utilTextService->dashToCamelCase($bundleName));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function extractTemplatePath($name)
    {
        $nameWithoutPrefix = $this->getNameWithoutPrefix($name);
        $firstSeparatorPosition = $this->getFirstSeparatorPosition($nameWithoutPrefix);

        $templatePath = substr($nameWithoutPrefix, $firstSeparatorPosition + 1);

        return $this->filterTemplatePath($templatePath);
    }

    /**
     * @param string $templatePath
     *
     * @return string
     */
    protected function filterTemplatePath($templatePath)
    {
        $templatePathParts = explode('/', $templatePath);
        $templateName = array_pop($templatePathParts);
        $templateName = $this->utilTextService->camelCaseToDash($templateName);

        array_push($templatePathParts, $templateName);
        $templatePath = implode('/', $templatePathParts);

        $firstSeparatorPosition = $this->getFirstSeparatorPosition($templatePath);

        if ($firstSeparatorPosition === false) {
            return $templatePath;
        }

        return ucfirst($templatePath);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getNameWithoutPrefix($name)
    {
        return ltrim($name, '@/');
    }

    /**
     * @param string $nameWithoutPrefix
     *
     * @return int|false
     */
    protected function getFirstSeparatorPosition($nameWithoutPrefix)
    {
        return strpos($nameWithoutPrefix, '/');
    }
}
