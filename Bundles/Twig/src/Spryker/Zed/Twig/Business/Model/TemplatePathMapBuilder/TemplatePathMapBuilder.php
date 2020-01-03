<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder;

use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface;
use Symfony\Component\Finder\Finder;

class TemplatePathMapBuilder implements TemplatePathMapBuilderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface
     */
    protected $templateNameBuilder;

    /**
     * @var string|array
     */
    protected $directory;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface $templateNameBuilder
     * @param string|array $directory
     */
    public function __construct(Finder $finder, TemplateNameBuilderInterface $templateNameBuilder, $directory)
    {
        $this->finder = $finder;
        $this->templateNameBuilder = $templateNameBuilder;
        $this->directory = $directory;
    }

    /**
     * @return array
     */
    public function build()
    {
        $templatePathMap = [];
        foreach ($this->getFinder() as $file) {
            $templateName = $this->templateNameBuilder->buildTemplateName($file->getRealPath());
            $templatePathMap[$templateName] = $file->getRealPath();

            if ($this->templateNameBuilder instanceof NamespacedTemplateNameBuilderInterface) {
                $namespacedTemplateName = $this->templateNameBuilder->buildNamespacedTemplateName($file->getRealPath());
                $templatePathMap[$namespacedTemplateName] = $file->getRealPath();
            }
        }

        return $templatePathMap;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo
     */
    protected function getFinder()
    {
        $this->finder->in($this->directory)->name('*.twig');

        return $this->finder;
    }
}
