<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig;

class ContentProductSetGuiEditorConfigurationMapper implements ContentProductSetGuiEditorConfigurationMapperInterface
{
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_ID = '%ID%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT = "{{ %s(%s, '%s') }}";

    /**
     * @var \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig
     */
    protected $contentProductSetGuiConfig;

    /**
     * @param \Spryker\Zed\ContentProductSetGui\ContentProductSetGuiConfig $contentProductSetGuiConfig
     */
    public function __construct(ContentProductSetGuiConfig $contentProductSetGuiConfig)
    {
        $this->contentProductSetGuiConfig = $contentProductSetGuiConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->contentProductSetGuiConfig->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
            $templates[] = (new ContentWidgetTemplateTransfer())
                ->setIdentifier($templateIdentifier)
                ->setName($templateName);
        }

        return $templates;
    }

    /**
     * @return string
     */
    public function getTwigFunctionTemplate(): string
    {
        return sprintf(
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT,
            $this->contentProductSetGuiConfig->getTwigFunctionName(),
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_ID,
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE
        );
    }
}
