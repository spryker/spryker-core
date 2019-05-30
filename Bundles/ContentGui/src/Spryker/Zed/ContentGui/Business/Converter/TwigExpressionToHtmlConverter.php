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
use Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface;

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
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $html = $this->convertTwigExpressionsToHtml($html, $contentEditorPlugin);
        }

        return $html;
    }

    /**
     * @param string $html
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface $contentEditorPlugin
     *
     * @return string
     */
    protected function convertTwigExpressionsToHtml(string $html, ContentGuiEditorPluginInterface $contentEditorPlugin): string
    {
        $twigExpressions = $this->findTwigExpressions($html, $contentEditorPlugin->getTwigFunctionTemplate());

        if (!$twigExpressions) {
            return $html;
        }

        return $this->replaceTwigExpressions($html, $twigExpressions, $contentEditorPlugin->getTemplates());
    }

    /**
     * @param string $html
     * @param string $twigFunctionTemplate
     *
     * @return array|null
     */
    protected function findTwigExpressions(string $html, string $twigFunctionTemplate): ?array
    {
        // Example: {{ content_banner(%ID%, '%TEMPLATE%') }} -> {{ content_banner(.+?) }}
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
        preg_match('/\d+/', $twigExpression, $idContent);

        if (!$idContent) {
            return null;
        }

        $idContent = (int)$idContent[0];
        $contentTransfer = $this->contentFacade->findContentById($idContent);

        if (!$contentTransfer) {
            return null;
        }

        $contentTypeKey = $this->translatorFacade->trans($contentTransfer->getContentTypeKey());
        $contentTransfer->setContentTypeKey($contentTypeKey);

        return $contentTransfer;
    }

    /**
     * @param string $twigFunction
     *
     * @return string|null
     */
    protected function findTemplateIdentifier(string $twigFunction): ?string
    {
        preg_match("/'[\w+\-]+'/", $twigFunction, $templateIdentifier);

        if (!$templateIdentifier) {
            return null;
        }

        return trim($templateIdentifier[0], "'");
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
