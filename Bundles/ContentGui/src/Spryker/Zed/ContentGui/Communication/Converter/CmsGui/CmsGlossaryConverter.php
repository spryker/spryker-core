<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Converter\CmsGui;

use DOMDocument;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;

class CmsGlossaryConverter implements CmsGlossaryConverterInterface
{
    protected const PARAMETER_ID = '%ID%';
    protected const PARAMETER_TYPE = '%TYPE%';
    protected const PARAMETER_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_FUNCTION = '%TWIG_FUNCTION%';
    protected const PARAMETER_NAME = '%NAME%';
    protected const PARAMETER_TEMPLATE_DISPLAY_NAME = '%TEMPLATE_DISPLAY_NAME%';
    protected const PATTERN_NUMERIC_REGEXP = '\d+';
    protected const PATTERN_STRING_REGEXP = '[\w+\-]+';
    protected const ATTRIBUTE_DATA_TWIG_FUNCTION = 'data-twig-function';

    /**
     * @var \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[]
     */
    protected $contentEditorPlugins;

    /**
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @var \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\ContentGuiConfig $config
     */
    public function __construct(
        array $contentEditorPlugins,
        ContentGuiToContentFacadeInterface $contentFacade,
        ContentGuiConfig $config
    ) {
        $this->contentEditorPlugins = $contentEditorPlugins;
        $this->contentFacade = $contentFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransferKey => $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransferKey => $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $cmsPlaceholderTranslation = $this->convertTwigToHtmlTranslation($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfers[$cmsPlaceholderTranslationTransferKey]->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfers[$cmsGlossaryAttributesTransferKey]->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToTwig(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransferKey => $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransferKey => $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $cmsPlaceholderTranslation = $this->convertHtmlToTwigTranslation($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfers[$cmsPlaceholderTranslationTransferKey]->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfers[$cmsGlossaryAttributesTransferKey]->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param string $cmsPlaceholderTranslation
     *
     * @return string
     */
    protected function convertTwigToHtmlTranslation(string $cmsPlaceholderTranslation): string
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $twigFunctions = $this->getTwigFunctions(
                $cmsPlaceholderTranslation,
                $contentEditorPlugin->getTwigFunctionTemplate()
            );

            $cmsPlaceholderTranslation = $this->replaceTwigFunctionsToHtml(
                $cmsPlaceholderTranslation,
                $twigFunctions,
                $contentEditorPlugin->getTemplates()
            );
        }

        return $cmsPlaceholderTranslation;
    }

    /**
     * @param string $cmsPlaceholderTranslation
     * @param string $twigFunctionTemplate
     *
     * @return array
     */
    protected function getTwigFunctions(string $cmsPlaceholderTranslation, string $twigFunctionTemplate): array
    {
        $twigFunctionTemplatePattern = $this->createTwigFunctionTemplatePattern($twigFunctionTemplate);
        preg_match($twigFunctionTemplatePattern, $cmsPlaceholderTranslation, $twigFunctions);

        return $twigFunctions;
    }

    /**
     * @param string $twigFunctionTemplate
     *
     * @return string
     */
    protected function createTwigFunctionTemplatePattern(string $twigFunctionTemplate): string
    {
        return strtr('/' . $twigFunctionTemplate . '/', [
            '(' => '\(',
            ')' => '\)',
            static::PARAMETER_ID => static::PATTERN_NUMERIC_REGEXP,
            static::PARAMETER_TEMPLATE => static::PATTERN_STRING_REGEXP,
        ]);
    }

    /**
     * @param string $cmsPlaceholderTranslation
     * @param array $twigFunctions
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function replaceTwigFunctionsToHtml(string $cmsPlaceholderTranslation, array $twigFunctions, array $contentWidgetTemplateTransfers): string
    {
        foreach ($twigFunctions as $twigFunction) {
            $idContentItem = $this->getIdContentItem($twigFunction);
            $templateIdentifier = $this->getTemplateIdentifier($twigFunction);
            $templateDisplayName = $this->getTempateDisplayName($templateIdentifier, $contentWidgetTemplateTransfers);
            $contentItem = $this->contentFacade->findContentById($idContentItem);
            $editorContentWidget = $this->getEditorContentWidget($contentItem, $templateIdentifier, $twigFunction, $templateDisplayName);

            $cmsPlaceholderTranslation = str_replace($twigFunction, $editorContentWidget, $cmsPlaceholderTranslation);
        }

        return $cmsPlaceholderTranslation;
    }

    /**
     * @param string $twigFunctionInTranslation
     *
     * @return string
     */
    protected function getTemplateIdentifier(string $twigFunctionInTranslation): string
    {
        preg_match('/\'' . static::PATTERN_STRING_REGEXP . '\'/', $twigFunctionInTranslation, $templateIdentifier);

        return str_replace('\'', '', $templateIdentifier[0]);
    }

    /**
     * @param string $templateIdentifier
     * @param array $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function getTempateDisplayName(string $templateIdentifier, array $contentWidgetTemplateTransfers): string
    {
        $templateName = $templateIdentifier;

        foreach ($contentWidgetTemplateTransfers as $contentWidgetTemplateTransfer) {
            if ($contentWidgetTemplateTransfer->getIdentifier() !== $templateIdentifier) {
                continue;
            }

            $templateName = $contentWidgetTemplateTransfer->getName();
        }

        return $templateName;
    }

    /**
     * @param string $twigFunctionInTranslation
     *
     * @return int
     */
    protected function getIdContentItem(string $twigFunctionInTranslation): int
    {
        preg_match('/' . static::PATTERN_NUMERIC_REGEXP . '/', $twigFunctionInTranslation, $idContentItem);

        return (int)$idContentItem[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentItem
     * @param string $templateIdentifier
     * @param string $twigFunction
     * @param string $templateDisplayName
     *
     * @return string
     */
    protected function getEditorContentWidget(
        ContentTransfer $contentItem,
        string $templateIdentifier,
        string $twigFunction,
        string $templateDisplayName
    ) {
        return strtr($this->config->getEditorContentWidgetHtml(), [
            static::PARAMETER_TYPE => $contentItem->getContentTypeKey(),
            static::PARAMETER_ID => $contentItem->getIdContent(),
            static::PARAMETER_TEMPLATE => $templateIdentifier,
            static::PARAMETER_TWIG_FUNCTION => $twigFunction,
            static::PARAMETER_NAME => $contentItem->getName(),
            static::PARAMETER_TEMPLATE_DISPLAY_NAME => $templateDisplayName,
        ]);
    }

    /**
     * @param string $cmsPlaceholderTranslation
     *
     * @return string
     */
    protected function convertHtmlToTwigTranslation(string $cmsPlaceholderTranslation): string
    {
        $dom = new DOMDocument();
        $dom->loadHTML($cmsPlaceholderTranslation, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $replacements = $this->getDomReplacements($dom);

        foreach ($replacements as $replacement) {
            [$twigFunction, $div] = $replacement;
            $div->parentNode->replaceChild($twigFunction, $div);
        }

        $cmsPlaceholderTranslation = $dom->saveHTML();
        unset($dom);

        return $cmsPlaceholderTranslation;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return array
     */
    protected function getDomReplacements(DOMDocument $dom): array
    {
        $replacements = [];
        $divs = $dom->getElementsByTagName('div');

        foreach ($divs as $div) {
            if (!$div->getAttribute(static::ATTRIBUTE_DATA_TWIG_FUNCTION)) {
                continue;
            }

            $dataTwigFunction = $div->getAttribute(static::ATTRIBUTE_DATA_TWIG_FUNCTION);
            $twigFunction = $dom->createDocumentFragment();
            $twigFunction->appendXML($dataTwigFunction);
            $replacements[] = [$twigFunction, $div];
        }

        return $replacements;
    }
}
