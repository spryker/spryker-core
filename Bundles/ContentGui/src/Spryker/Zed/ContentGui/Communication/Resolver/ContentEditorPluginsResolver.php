<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

class ContentEditorPluginsResolver implements ContentEditorPluginsResolverInterface
{
    /**
     * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    protected $contentEditorPlugins;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     */
    public function __construct(array $contentEditorPlugins)
    {
        $this->contentEditorPlugins = $contentEditorPlugins;
    }

    /**
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    public function getTemplatesByType(string $contentType): array
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            if ($contentEditorPlugin->getType() !== $contentType) {
                continue;
            }

            return $contentEditorPlugin->getTemplates();
        }

        return [];
    }

    /**
     * @param string $contentType
     *
     * @return string
     */
    public function getTwigFunctionTemplateByType(string $contentType): string
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            if ($contentEditorPlugin->getType() !== $contentType) {
                continue;
            }

            return $contentEditorPlugin->getTwigFunctionTemplate();
        }

        return '';
    }
}
