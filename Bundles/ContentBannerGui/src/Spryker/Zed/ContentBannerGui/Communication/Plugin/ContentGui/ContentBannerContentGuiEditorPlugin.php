<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerGui\Communication\Plugin\ContentGui;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Shared\ContentBannerGui\ContentBannerGuiConfig;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;

class ContentBannerContentGuiEditorPlugin implements ContentGuiEditorPluginInterface
{
    protected const PARAMETER_MACRO_ID = '%ID%';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getType(): string
    {
        return ContentBannerGuiConfig::CONTENT_TYPE_BANNER;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplates(): array
    {
        return $this->mapTemplates();
    }

    /**
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    protected function mapTemplates(): array
    {
        $templates = [];

        foreach (ContentBannerGuiConfig::TEMPLATE_NAMES as $templateIdentifier => $templateName) {
            $templates[] = (new ContentWidgetTemplateTransfer())
                ->setIdentifier($templateIdentifier)
                ->setName($templateName)
                ->setMacro($this->createMacro($templateIdentifier));
        }

        return $templates;
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function createMacro(string $templateIdentifier): string
    {
        $functionName = ContentBannerGuiConfig::FUNCTION_NAME;
        $idParameter = static::PARAMETER_MACRO_ID;

        return sprintf("{{ %s(%s, '%s') }}", $functionName, $idParameter, $templateIdentifier);
    }
}
