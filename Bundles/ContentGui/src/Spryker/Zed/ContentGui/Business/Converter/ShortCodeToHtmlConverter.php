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

class ShortCodeToHtmlConverter implements ContentGuiConverterInterface
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
     * @param string $string
     *
     * @return string
     */
    public function convert(string $string): string
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $string = $this->convertShortCodesToHtml($string, $contentEditorPlugin);
        }

        return $string;
    }

    /**
     * @param string $string
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface $contentEditorPlugin
     *
     * @return string
     */
    protected function convertShortCodesToHtml(string $string, ContentGuiEditorPluginInterface $contentEditorPlugin): string
    {
        $shortCodes = $this->extractShortCodes($string, $contentEditorPlugin->getTwigFunctionTemplate());

        if (!$shortCodes) {
            return $string;
        }

        return $this->replaceShortCodes($string, $shortCodes, $contentEditorPlugin->getTemplates());
    }

    /**
     * @param string $string
     * @param string $twigFunctionTemplate
     *
     * @return array|null
     */
    protected function extractShortCodes(string $string, string $twigFunctionTemplate): ?array
    {
        $shortCodeRegExpPattern = strtr('/' . $twigFunctionTemplate . '/', [
            '(' => '\(',
            ')' => '\)',
            ContentGuiConfig::PARAMETER_ID => static::PATTERN_REGEXP_NUMERIC,
            ContentGuiConfig::PARAMETER_TEMPLATE => static::PATTERN_REGEXP_STRING,
        ]);

        preg_match_all($shortCodeRegExpPattern, $string, $shortCodes);

        if (!$shortCodes[0]) {
            return null;
        }

        return $shortCodes[0];
    }

    /**
     * @param string $string
     * @param string[] $shortCodes
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function replaceShortCodes(string $string, array $shortCodes, array $contentWidgetTemplateTransfers): string
    {
        foreach ($shortCodes as $shortCode) {
            $editorContentWidget = $this->getEditorContentWidgetByShortCode($shortCode, $contentWidgetTemplateTransfers);

            if (!$editorContentWidget) {
                continue;
            }

            $string = str_replace($shortCode, $editorContentWidget, $string);
        }

        return $string;
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
            ContentGuiConfig::PARAMETER_ID => $contentTransfer->getIdContent(),
            ContentGuiConfig::PARAMETER_TYPE => $contentTransfer->getContentTypeKey(),
            ContentGuiConfig::PARAMETER_TEMPLATE => $templateIdentifier,
            ContentGuiConfig::PARAMETER_SHORT_CODE => $shortCode,
            ContentGuiConfig::PARAMETER_NAME => $contentTransfer->getName(),
            ContentGuiConfig::PARAMETER_TEMPLATE_DISPLAY_NAME => $templateDisplayName,
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
                if (!$contentWidgetTemplateTransfer->getName()) {
                    return '';
                }

                return $this->translatorFacade->trans($contentWidgetTemplateTransfer->getName());
            }
        }

        return '';
    }
}
