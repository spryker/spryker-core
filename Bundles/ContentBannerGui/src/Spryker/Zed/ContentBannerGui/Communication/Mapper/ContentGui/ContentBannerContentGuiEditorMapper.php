<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Mapper\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;

class ContentBannerContentGuiEditorMapper implements ContentBannerContentGuiEditorMapperInterface
{
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_ID = '%ID%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION_TEMPLATE_FORMAT = "{{ %s(%s, '%s') }}";

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach (ContentBannerGuiConfig::TEMPLATE_NAMES as $templateIdentifier => $templateName) {
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
            ContentBannerGuiConfig::FUNCTION_NAME,
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_ID,
            static::PARAMETER_TWIG_FUNCTION_TEMPLATE_TEMPLATE
        );
    }
}
