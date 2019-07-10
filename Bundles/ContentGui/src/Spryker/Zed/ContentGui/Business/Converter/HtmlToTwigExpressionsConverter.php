<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use DOMDocument;
use DOMNode;
use DOMText;
use DOMXPath;
use Spryker\Zed\ContentGui\Business\Exception\HtmlConverterException;
use Spryker\Zed\ContentGui\ContentGuiConfig;

class HtmlToTwigExpressionsConverter implements HtmlToTwigExpressionsConverterInterface
{
    protected const ERROR_MESSAGE_MAX_WIDGET_NUMBER = 'Limit exceeded, maximum number of widgets %d';
    protected const HTML_OUTPUT_ENCODING = 'HTML-ENTITIES';
    protected const HTML_INPUT_ENCODING = 'UTF-8';

    /**
     * @var \DOMDocument
     */
    protected $domDocument;

    /**
     * @var \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected $contentGuiConfig;

    /**
     * @param \DOMDocument $domDocument
     * @param \Spryker\Zed\ContentGui\ContentGuiConfig $contentGuiConfig
     */
    public function __construct(DOMDocument $domDocument, ContentGuiConfig $contentGuiConfig)
    {
        $this->domDocument = $domDocument;
        $this->contentGuiConfig = $contentGuiConfig;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function convert(string $html): string
    {
        $this->assureMaxWidgetNumberIsNotExceeded($html);

        $this->domDocument->loadHTML(
            $this->getHtmlForDomDocumentLoading($html),
            LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $replaceableNodes = $this->getReplaceableNodes();

        if (!$replaceableNodes) {
            return $html;
        }

        $this->replaceNodes($replaceableNodes);

        return $this->getHtmlFromDomDocumentSaving($this->domDocument->saveHTML());
    }

    /**
     * Libxml requires a root node, for which <html> is used.
     * `DomDocument::loadHTML()` converts non ISO-8859-1 charsets, so the string encoding must be converted before loading.
     *
     * @param string $html
     *
     * @return string
     */
    protected function getHtmlForDomDocumentLoading(string $html): string
    {
        return mb_convert_encoding("<html>$html</html>", static::HTML_OUTPUT_ENCODING, static::HTML_INPUT_ENCODING);
    }

    /**
     * @param string $html
     *
     * @return string
     */
    protected function getHtmlFromDomDocumentSaving(string $html): string
    {
        return rtrim(str_replace(['<html>', '</html>'], '', $html));
    }

    /**
     * @return array
     */
    protected function getReplaceableNodes(): array
    {
        $replacements = [];
        $domXpath = $this->createDOMXPath();
        $widgets = $domXpath->query($this->contentGuiConfig->getWidgetXpathQuery());

        foreach ($widgets as $widget) {
            $twigExpression = $this->domDocument->createTextNode($widget->getAttribute('data-twig-expression'));
            $replacements[] = $this->addReplacement($twigExpression, $widget);
        }

        return array_reverse($replacements);
    }

    /**
     * @param \DOMText $twigExpression
     * @param \DOMNode $oldNode
     *
     * @return \DOMNode[]
     */
    protected function addReplacement(DOMText $twigExpression, DOMNode $oldNode): array
    {
        return ['oldNode' => $oldNode, 'newNode' => $twigExpression];
    }

    /**
     * @return \DOMXPath
     */
    protected function createDOMXPath(): DOMXPath
    {
        return new DOMXPath($this->domDocument);
    }

    /**
     * @param array $replacement
     *
     * @return void
     */
    protected function replaceElementsWithoutWrappers(array $replacement): void
    {
        $parentNode = $replacement['oldNode']->parentNode;
        $p = $this->domDocument->createElement('p');
        $parentNode->insertBefore($p, $replacement['oldNode']);
        $p->appendChild($replacement['oldNode']);
        $parentNode->insertBefore($replacement['newNode'], $p);
        $parentNode->removeChild($p);
    }

    /**
     * @param array $replaceableNodes
     *
     * @return void
     */
    protected function replaceNodes(array $replaceableNodes): void
    {
        foreach ($replaceableNodes as $key => $replacement) {
            if ($replacement['oldNode']->parentNode->tagName === 'html') {
                $this->replaceElementsWithoutWrappers($replacement);
                continue;
            }

            $parentNode = $replacement['oldNode']->parentNode->parentNode;
            $parentNode->insertBefore($replacement['newNode'], $replacement['oldNode']->parentNode);
            $nextKey = $key + 1;

            if (!isset($replaceableNodes[$nextKey]['oldNode']) || !$replaceableNodes[$nextKey]['oldNode']->parentNode->isSameNode($replacement['oldNode']->parentNode)) {
                $parentNode->removeChild($replacement['oldNode']->parentNode);
            }
        }
    }

    /**
     * @param string $html
     *
     * @throws \Spryker\Zed\ContentGui\Business\Exception\HtmlConverterException
     *
     * @return void
     */
    protected function assureMaxWidgetNumberIsNotExceeded(string $html): void
    {
        if (mb_substr_count($html, 'data-twig-expression') > $this->contentGuiConfig->getMaxWidgetNumber()) {
            throw new HtmlConverterException(sprintf(static::ERROR_MESSAGE_MAX_WIDGET_NUMBER, $this->contentGuiConfig->getMaxWidgetNumber()));
        }
    }
}
