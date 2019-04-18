<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Resolver;

use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

class ContentEditorPluginsResolver implements ContentEditorPluginsResolverInterface
{
    /**
     * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    protected $contentEditorPlugins;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(array $contentEditorPlugins, ContentGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->contentEditorPlugins = $contentEditorPlugins;
        $this->translatorFacade = $translatorFacade;
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

            return $this->resolveTemplates($contentEditorPlugin->getTemplates());
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $templates
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[]
     */
    protected function resolveTemplates(array $templates): array
    {
        foreach ($templates as $key => $template) {
            $template->setName($this->translatorFacade->trans($template->getName()));
            $templates[$key] = $template;
        }

        return $templates;
    }
}
