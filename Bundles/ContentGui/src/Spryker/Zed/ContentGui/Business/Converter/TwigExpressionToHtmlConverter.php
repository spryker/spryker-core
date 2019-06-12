<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

class TwigExpressionToHtmlConverter implements TwigExpressionConverterInterface
{
    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected $contentGuiConfig;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    protected $contentEditorPlugins;

    /**
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\ContentGuiConfig $contentGuiConfig
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     */
    public function __construct(
        ContentGuiToContentFacadeInterface $contentFacade,
        ContentGuiConfig $contentGuiConfig,
        ContentGuiToTranslatorFacadeInterface $translatorFacade,
        array $contentEditorPlugins
    ) {
        $this->contentFacade = $contentFacade;
        $this->contentGuiConfig = $contentGuiConfig;
        $this->translatorFacade = $translatorFacade;
        $this->contentEditorPlugins = $contentEditorPlugins;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function convertTwigExpressionToHtml(string $html): string
    {
        if (mb_substr_count($html, '{{ content_') > $this->contentGuiConfig->getMaxWidgetNumber()) {
            return $html;
        }

        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $twigExpressions = $this->findTwigExpressions($html, $contentEditorPlugin->getTwigFunctionTemplate());

            if (!$twigExpressions) {
                continue;
            }

            $html = $this->replaceTwigExpressions($html, $twigExpressions, $contentEditorPlugin->getTemplates());
        }

        return $html;
    }

    /**
     * @param string $html
     * @param string $twigFunctionTemplate
     *
     * @return array|null
     */
    protected function findTwigExpressions(string $html, string $twigFunctionTemplate): ?array
    {
        // Example: {{ content_banner('%KEY%', '%TEMPLATE%') }} -> {{ content_banner(.+?) }}
        $twigExpressionPattern = preg_replace('/\(.+\)/', '\(.+?\)', $twigFunctionTemplate);
        preg_match_all('/' . $twigExpressionPattern . '/', $html, $twigExpressions);

        if (!$twigExpressions[0]) {
            return null;
        }

        return $twigExpressions[0];
    }

    /**
     * @param string $html
     * @param string[] $twigExpressions
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function replaceTwigExpressions(string $html, array $twigExpressions, array $contentWidgetTemplateTransfers): string
    {
        $twigExpressionReplacements = [];

        foreach ($twigExpressions as $twigExpression) {
            if (isset($twigExpressionReplacements[$twigExpression])) {
                continue;
            }

            $editorContentWidget = $this->getEditorContentWidgetByTwigExpression($twigExpression, $contentWidgetTemplateTransfers);
            if (!$editorContentWidget) {
                continue;
            }

            $twigExpressionReplacements[$twigExpression] = sprintf($this->contentGuiConfig->getEditorContentWidgetWrapper(), $editorContentWidget);
        }

        return strtr($html, $twigExpressionReplacements);
    }

    /**
     * @param string $twigExpression
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string|null
     */
    protected function getEditorContentWidgetByTwigExpression(string $twigExpression, array $contentWidgetTemplateTransfers): ?string
    {
        $contentTransfer = $this->findContentItem($twigExpression);

        if (!$contentTransfer) {
            return null;
        }

        $templateIdentifier = $this->findTemplateIdentifier($twigExpression);
        $templateDisplayName = '';

        if ($templateIdentifier) {
            $templateDisplayName = $this->getTemplateDisplayNameByIdentifier($templateIdentifier, $contentWidgetTemplateTransfers);
        }

        return strtr($this->contentGuiConfig->getEditorContentWidgetTemplate(), [
            $this->contentGuiConfig->getParameterId() => $contentTransfer->getIdContent(),
            $this->contentGuiConfig->getParameterKey() => $contentTransfer->getKey(),
            $this->contentGuiConfig->getParameterType() => $contentTransfer->getContentTypeKey(),
            $this->contentGuiConfig->getParameterName() => $contentTransfer->getName(),
            $this->contentGuiConfig->getParameterTwigExpression() => $twigExpression,
            $this->contentGuiConfig->getParameterTemplate() => $templateIdentifier,
            $this->contentGuiConfig->getParameterTemplateDisplayName() => $templateDisplayName,
        ]);
    }

    /**
     * @param string $twigExpression
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    protected function findContentItem(string $twigExpression): ?ContentTransfer
    {
        preg_match("/'([\w\-]+)'/", $twigExpression, $twigExpressionParams);

        if (!isset($twigExpressionParams[1])) {
            return null;
        }

        $contentKey = $twigExpressionParams[1];
        $contentTransfer = $this->contentFacade->findContentByKey($contentKey);

        if (!$contentTransfer) {
            return null;
        }

        $contentTypeKey = $this->translatorFacade->trans($contentTransfer->getContentTypeKey());
        $contentTransfer->setContentTypeKey($contentTypeKey);

        return $contentTransfer;
    }

    /**
     * @param string $twigExpression
     *
     * @return string|null
     */
    protected function findTemplateIdentifier(string $twigExpression): ?string
    {
        preg_match_all("/'([\w\-]+)'/", $twigExpression, $twigExpressionParams);

        if (!isset($twigExpressionParams[1][1])) {
            return null;
        }

        return $twigExpressionParams[1][1];
    }

    /**
     * @param string $templateIdentifier
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function getTemplateDisplayNameByIdentifier(string $templateIdentifier, array $contentWidgetTemplateTransfers): string
    {
        foreach ($contentWidgetTemplateTransfers as $contentWidgetTemplateTransfer) {
            if ($contentWidgetTemplateTransfer->getIdentifier() === $templateIdentifier) {
                return $this->translatorFacade->trans($contentWidgetTemplateTransfer->getName());
            }
        }

        return '';
    }
}
