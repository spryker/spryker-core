<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentFileGui\ContentFileGuiConfig;

class ContentFileGuiEditorConfigurationMapper implements ContentFileGuiEditorConfigurationMapperInterface
{
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_ID = '%ID%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT = "{{ %s(%s, '%s') }}";

    /**
     * @var \Spryker\Zed\ContentFileGui\ContentFileGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentFileGui\ContentFileGuiConfig $config
     */
    public function __construct(ContentFileGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach ($this->config->getContentWidgetTemplates() as $templateIdentifier => $templateName) {
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
            $this->config->getTwigFunctionName(),
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_ID,
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE
        );
    }
}
