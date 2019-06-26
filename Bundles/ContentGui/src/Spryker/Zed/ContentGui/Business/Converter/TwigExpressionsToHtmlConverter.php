<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentWidgetTemplateTransfer;
use Generated\Shared\Transfer\TwigExpressionTransfer;
use Spryker\Zed\ContentGui\Business\Exception\HtmlConverterException;
use Spryker\Zed\ContentGui\ContentGuiConfig;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToContentFacadeInterface;
use Spryker\Zed\ContentGui\Dependency\Facade\ContentGuiToTranslatorFacadeInterface;

class TwigExpressionsToHtmlConverter implements TwigExpressionsToHtmlConverterInterface
{
    protected const ERROR_MESSAGE_MAX_WIDGET_NUMBER = 'Limit exceeded, maximum number of widgets %d';

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
     * @param string $htmlWithTwigExpressions
     *
     * @return string
     */
    public function convert(string $htmlWithTwigExpressions): string
    {
        $this->assureMaxWidgetNumbersIsNotExceeded($htmlWithTwigExpressions);

        $twigExpressionTransfers = $this->findTwigExpressions($htmlWithTwigExpressions);

        return $this->replaceTwigExpressions($htmlWithTwigExpressions, $twigExpressionTransfers);
    }

    /**
     * @param string $html
     *
     * @throws \Spryker\Zed\ContentGui\Business\Exception\HtmlConverterException
     *
     * @return void
     */
    protected function assureMaxWidgetNumbersIsNotExceeded(string $html): void
    {
        if (mb_substr_count($html, '{{ content_') > $this->contentGuiConfig->getMaxWidgetNumber()) {
            throw new HtmlConverterException(sprintf(static::ERROR_MESSAGE_MAX_WIDGET_NUMBER, $this->contentGuiConfig->getMaxWidgetNumber()));
        }
    }

    /**
     * @param string $html
     *
     * @return array
     */
    protected function findTwigExpressions(string $html): array
    {
        $twigExpressionTransfers = [];

        foreach ($this->contentEditorPlugins as $contentEditorPlugin) {
            // Example: {{ content_banner('%KEY%', '%TEMPLATE%') }} -> {{ content_banner('([\w-]+)', '([\w-]+)') }}
            $twigExpressionPattern = preg_replace(
                "/\('%KEY%', '%TEMPLATE%'\)/",
                "\('([\w-]+)', '([\w-]+)'\)",
                $contentEditorPlugin->getTwigFunctionTemplate()
            );

            preg_match_all('/' . $twigExpressionPattern . '/', $html, $twigExpressionMatches);

            if (!$twigExpressionMatches[0]) {
                continue;
            }

            $twigExpressionTransfers = $this->mapTwigExpressionsToTransfers(
                $twigExpressionMatches,
                $contentEditorPlugin->getTemplates(),
                $twigExpressionTransfers
            );
        }

        return $twigExpressionTransfers;
    }

    /**
     * @param array $twigExpressionMatches
     * @param array $contentWidgetTemplateTransfers
     * @param array $twigExpressionTransfers
     *
     * @return array
     */
    protected function mapTwigExpressionsToTransfers(
        array $twigExpressionMatches,
        array $contentWidgetTemplateTransfers,
        array $twigExpressionTransfers
    ): array {
        foreach ($twigExpressionMatches[0] as $key => $twigExpressionMatch) {
            if (!isset($twigExpressionMatches[1][$key])) {
                continue;
            }

            $contentKey = $twigExpressionMatches[1][$key];
            $contentTransfer = $this->findContentItemByKey($contentKey);
            if (!$contentTransfer) {
                continue;
            }

            $contentWidgetTemplateTransfer = new ContentWidgetTemplateTransfer();
            if (isset($twigExpressionMatches[2][$key])) {
                $templateIdentifier = $twigExpressionMatches[2][$key];
                $contentWidgetTemplateTransfer = $this->getContentWidgetTemplateByIdentifier($templateIdentifier, $contentWidgetTemplateTransfers);
            }

            $twigExpressionTransfers[] =
                (new TwigExpressionTransfer())
                    ->setContent($contentTransfer)
                    ->setContentWidgetTemplate($contentWidgetTemplateTransfer)
                    ->setTwigExpression($twigExpressionMatch);
        }

        return $twigExpressionTransfers;
    }

    /**
     * @param string $contentKey
     *
     * @return \Generated\Shared\Transfer\ContentTransfer|null
     */
    protected function findContentItemByKey(string $contentKey): ?ContentTransfer
    {
        $contentTransfer = $this->contentFacade->findContentByKey($contentKey);

        if (!$contentTransfer) {
            return null;
        }

        return $contentTransfer;
    }

    /**
     * @param string $templateIdentifier
     * @param array $contentWidgetTemplateTransfers
     *
     * @return \Generated\Shared\Transfer\ContentWidgetTemplateTransfer
     */
    protected function getContentWidgetTemplateByIdentifier(
        string $templateIdentifier,
        array $contentWidgetTemplateTransfers
    ): ContentWidgetTemplateTransfer {
        foreach ($contentWidgetTemplateTransfers as $contentWidgetTemplateTransfer) {
            if ($contentWidgetTemplateTransfer->getIdentifier() === $templateIdentifier) {
                return $contentWidgetTemplateTransfer->setName(
                    $this->translatorFacade->trans($contentWidgetTemplateTransfer->getName())
                );
            }
        }

        return (new ContentWidgetTemplateTransfer())
            ->setName($templateIdentifier)
            ->setIdentifier($templateIdentifier);
    }

    /**
     * @param string $html
     * @param array $twigExpressionTransfers
     *
     * @return string
     */
    protected function replaceTwigExpressions(string $html, array $twigExpressionTransfers): string
    {
        if (!$twigExpressionTransfers) {
            return $html;
        }

        $replacements = [];

        foreach ($twigExpressionTransfers as $twigExpressionTransfer) {
            if (isset($replacements[$twigExpressionTransfer->getTwigExpression()])) {
                continue;
            }

            $replacements[$twigExpressionTransfer->getTwigExpression()] = $this->getWidgetByTwigExpression($twigExpressionTransfer);
        }

        return strtr($html, $replacements);
    }

    /**
     * @param \Generated\Shared\Transfer\TwigExpressionTransfer $twigExpressionTransfer
     *
     * @return string
     */
    protected function getWidgetByTwigExpression(TwigExpressionTransfer $twigExpressionTransfer): string
    {
        $editorContentWidget = strtr($this->contentGuiConfig->getEditorContentWidgetTemplate(), [
            $this->contentGuiConfig->getParameterId() => $twigExpressionTransfer->getContent()->getIdContent(),
            $this->contentGuiConfig->getParameterKey() => $twigExpressionTransfer->getContent()->getKey(),
            $this->contentGuiConfig->getParameterType() => $twigExpressionTransfer->getContent()->getContentTypeKey(),
            $this->contentGuiConfig->getParameterDisplayType() => $this->translatorFacade->trans($twigExpressionTransfer->getContent()->getContentTypeKey()),
            $this->contentGuiConfig->getParameterName() => $twigExpressionTransfer->getContent()->getName(),
            $this->contentGuiConfig->getParameterTwigExpression() => $twigExpressionTransfer->getTwigExpression(),
            $this->contentGuiConfig->getParameterTemplate() => $twigExpressionTransfer->getContentWidgetTemplate()->getIdentifier(),
            $this->contentGuiConfig->getParameterTemplateDisplayName() => $twigExpressionTransfer->getContentWidgetTemplate()->getName(),
        ]);

        return sprintf($this->contentGuiConfig->getEditorContentWidgetWrapper(), $editorContentWidget);
    }
}
