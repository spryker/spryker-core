<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Plugin;

use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;

class ContentBannerContentGuiEditorPluginMock implements ContentGuiEditorPluginInterface
{
    /**
     * @var array<string, string>
     */
    protected const TEMPLATES = [
        'default' => 'Default',
        'top-title' => 'Top Title',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Banner';
    }

    /**
     * @return array<\Generated\Shared\Transfer\ContentWidgetTemplateTransfer>
     */
    public function getTemplates(): array
    {
        $templates = [];

        foreach (static::TEMPLATES as $templateIdentifier => $templateName) {
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
        return "{{ content_banner('%KEY%', '%TEMPLATE%') }}";
    }
}
