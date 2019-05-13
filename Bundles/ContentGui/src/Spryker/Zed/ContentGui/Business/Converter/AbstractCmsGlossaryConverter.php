<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use DOMDocument;
use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

abstract class AbstractCmsGlossaryConverter
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
     * @var \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ContentGuiExtension\Dependency\Plugin\ContentGuiEditorPluginInterface[] $contentEditorPlugins
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface $contentFacade
     * @param \Spryker\Zed\ContentGui\ContentGuiConfig $config
     * @param \Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        array $contentEditorPlugins,
        ContentGuiToContentFacadeInterface $contentFacade,
        ContentGuiConfig $config,
        ContentGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->contentEditorPlugins = $contentEditorPlugins;
        $this->contentFacade = $contentFacade;
        $this->config = $config;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param string $translation
     *
     * @return string
     */
    protected function convertTwigFunctionToHtmlInTranslation(string $translation): string
    {
        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            $twigFunctions = $this->getTwigFunctions(
                $translation,
                $contentEditorPlugin->getTwigFunctionTemplate()
            );

            $translation = $this->replaceTwigFunctionsToHtml(
                $translation,
                $twigFunctions,
                $contentEditorPlugin->getTemplates()
            );
        }

        return $translation;
    }

    /**
     * @param string $translation
     * @param string $twigFunctionTemplate
     *
     * @return array
     */
    protected function getTwigFunctions(string $translation, string $twigFunctionTemplate): array
    {
        $twigFunctionTemplatePattern = $this->createTwigFunctionTemplatePattern($twigFunctionTemplate);
        preg_match($twigFunctionTemplatePattern, $translation, $twigFunctions);

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
     * @param string $translation
     * @param array $twigFunctions
     * @param \Generated\Shared\Transfer\ContentWidgetTemplateTransfer[] $contentWidgetTemplateTransfers
     *
     * @return string
     */
    protected function replaceTwigFunctionsToHtml(string $translation, array $twigFunctions, array $contentWidgetTemplateTransfers): string
    {
        foreach ($twigFunctions as $twigFunction) {
            $idContentItem = $this->getIdContentItem($twigFunction);
            $templateIdentifier = $this->getTemplateIdentifier($twigFunction);
            $templateDisplayName = $this->getTempateDisplayName($templateIdentifier, $contentWidgetTemplateTransfers);
            $contentItem = $this->contentFacade->findContentById($idContentItem);
            $editorContentWidget = $this->getEditorContentWidget($contentItem, $templateIdentifier, $twigFunction, $templateDisplayName);

            $translation = str_replace($twigFunction, $editorContentWidget, $translation);
        }

        return $translation;
    }

    /**
     * @param string $twigFunction
     *
     * @return string
     */
    protected function getTemplateIdentifier(string $twigFunction): string
    {
        preg_match('/\'' . static::PATTERN_STRING_REGEXP . '\'/', $twigFunction, $templateIdentifier);

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
    ): string {
        return strtr($this->config->getEditorContentWidgetTemplate(), [
            static::PARAMETER_ID => $contentItem->getIdContent(),
            static::PARAMETER_TYPE => $this->translatorFacade->trans($contentItem->getContentTypeKey()),
            static::PARAMETER_TEMPLATE => $templateIdentifier,
            static::PARAMETER_TWIG_FUNCTION => $twigFunction,
            static::PARAMETER_NAME => $contentItem->getName(),
            static::PARAMETER_TEMPLATE_DISPLAY_NAME => $this->translatorFacade->trans($templateDisplayName),
        ]);
    }

    /**
     * @param string $translation
     *
     * @return string
     */
    protected function convertHtmlToTwigFunctionInTranslation(string $translation): string
    {
        $dom = new DOMDocument();
        $dom->loadHTML($translation, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $replacements = $this->getDomReplacements($dom);

        foreach ($replacements as $replacement) {
            [$twigFunction, $div] = $replacement;
            $div->parentNode->replaceChild($twigFunction, $div);
        }

        $translation = $dom->saveHTML();
        unset($dom);

        return $translation;
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
