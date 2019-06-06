<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentProductGui\ContentProductGuiConfig;

class ContentProductContentGuiEditorConfigurationMapper implements ContentProductContentGuiEditorConfigurationMapperInterface
{
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_KEY = '%KEY%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT = "{{ %s('%s', '%s') }}";

    /**
     * @var \Spryker\Zed\ContentProductGui\ContentProductGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentProductGui\ContentProductGuiConfig $config
     */
    public function __construct(ContentProductGuiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
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
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_KEY,
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE
        );
    }
}
