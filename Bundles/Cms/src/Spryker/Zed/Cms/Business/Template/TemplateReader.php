<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;

class TemplateReader implements TemplateReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateContentReaderInterface
     */
    protected $templateContentReader;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface
     */
    protected $templatePlaceholderParser;

    /**
     * @param \Spryker\Zed\Cms\Business\Template\TemplateContentReaderInterface $templateContentReader
     * @param \Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface $templatePlaceholderParser
     */
    public function __construct(
        TemplateContentReaderInterface $templateContentReader,
        TemplatePlaceholderParserInterface $templatePlaceholderParser
    ) {
        $this->templateContentReader = $templateContentReader;
        $this->templatePlaceholderParser = $templatePlaceholderParser;
    }

    /**
     * @param string $templatePath
     *
     * @return string[]
     */
    public function getPlaceholdersByTemplatePath(string $templatePath): array
    {
        try {
            $content = $this->templateContentReader->getTemplateContent($templatePath);
        } catch (TemplateFileNotFoundException $exception) {
            return [];
        }

        return $this->templatePlaceholderParser->getTemplatePlaceholders($content);
    }
}
