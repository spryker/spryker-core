<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig;

class ContentBannerContentGuiEditorConfigurationMapper implements ContentBannerContentGuiEditorConfigurationMapperInterface
{
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_ID = '%ID%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT = "{{ %s(%s, '%s') }}";

    /**
     * @var \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentBannerGui\ContentBannerGuiConfig $config
     */
    public function __construct(ContentBannerGuiConfig $config)
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
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_TEMPLATE
        );
    }
}
