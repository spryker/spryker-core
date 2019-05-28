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

class ShortCodeToHtmlConverter implements ShortCodeConverterInterface
{
    protected const PATTERN_REGEXP_NUMERIC = '\d+';
    protected const PATTERN_REGEXP_STRING = '[\w+\-]+';

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
    public function replaceShortCode(string $html): string
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $html = $this->convertShortCodesToHtml($html, $contentEditorPlugin);
        }

        return $html;
    }

    /**
     * @param string $html
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface $contentEditorPlugin
     *
     * @return string
     */
    protected function convertShortCodesToHtml(string $html, ContentGuiEditorPluginInterface $contentEditorPlugin): string
    {
        $shortCodes = $this->extractShortCodes($html, $contentEditorPlugin->getTwigFunctionTemplate());

        if (!$shortCodes) {
            return $html;
        }

        return $this->replaceShortCodes($html, $shortCodes, $contentEditorPlugin->getTemplates());
    }

    /**
     * @param string $html
     * @param string $twigFunctionTemplate
     *
     * @return array|null
     */
    protected function extractShortCodes(string $html, string $twigFunctionTemplate): ?array
    {
        $shortCodeRegExpPattern = strtr('/' . $twigFunctionTemplate . '/', [
            '(' => '\(',
            ')' => '\)',
            $this->contentGuiConfig->getParameterId() => static::PATTERN_REGEXP_NUMERIC,
            $this->contentGuiConfig->getParameterTemplate() => static::PATTERN_REGEXP_STRING,
        ]);

        preg_match_all($shortCodeRegExpPattern, $html, $shortCodes);

        if (!$shortCodes[0]) {
            return null;
        }

        return $shortCodes[0];
    }

    /**
     * @param string $html
     * @param string[] $shortCodes
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function replaceShortCodes(string $html, array $shortCodes, array $contentWidgetTemplateTransfers): string
    {
        $shortCodeReplacements = [];

        foreach ($shortCodes as $shortCode) {
            if (isset($shortCodeReplacements[$shortCode])) {
                continue;
            }

            $editorContentWidget = $this->getEditorContentWidgetByShortCode($shortCode, $contentWidgetTemplateTransfers);
            if (!$editorContentWidget) {
                continue;
            }

            $shortCodeReplacements[$shortCode] = sprintf($this->contentGuiConfig->getEditorContentWidgetWrapper(), $editorContentWidget);
        }

        return strtr($html, $shortCodeReplacements);
    }

    /**
     * @param string $shortCode
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string|null
     */
    protected function getEditorContentWidgetByShortCode(string $shortCode, array $contentWidgetTemplateTransfers): ?string
    {
        $contentTransfer = $this->extractContentItem($shortCode);

        if (!$contentTransfer) {
            return null;
        }

        $templateIdentifier = $this->extractTemplateIdentifier($shortCode);
        $templateDisplayName = '';

        if ($templateIdentifier) {
            $templateDisplayName = $this->getTemplateDisplayNameByIdentifier($templateIdentifier, $contentWidgetTemplateTransfers);
        }

        return strtr($this->contentGuiConfig->getEditorContentWidgetTemplate(), [
            $this->contentGuiConfig->getParameterId() => $contentTransfer->getIdContent(),
            $this->contentGuiConfig->getParameterType() => $contentTransfer->getContentTypeKey(),
            $this->contentGuiConfig->getParameterName() => $contentTransfer->getName(),
            $this->contentGuiConfig->getParameterShortCode() => $shortCode,
            $this->contentGuiConfig->getParameterTemplate() => $templateIdentifier,
            $this->contentGuiConfig->getParameterTemplateDisplayName() => $templateDisplayName,
        ]);
    }

    /**
     * @param string $shortCode
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    protected function extractContentItem(string $shortCode): ?ContentTransfer
    {
        preg_match('/' . static::PATTERN_REGEXP_NUMERIC . '/', $shortCode, $idContent);

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
    protected function extractTemplateIdentifier(string $twigFunction): ?string
    {
        preg_match("/'" . static::PATTERN_REGEXP_STRING . "'/", $twigFunction, $templateIdentifier);

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
