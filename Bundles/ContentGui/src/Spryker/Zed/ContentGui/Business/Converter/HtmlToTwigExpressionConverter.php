<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use DOMDocument;
use DOMXPath;

class HtmlToTwigExpressionConverter implements HtmlConverterInterface
{
    /**
     * @param string $html
     *
     * @return string
     */
    public function convertHtmlToTwigExpression(string $html): string
    {
        $dom = $this->getParsedDocument($html);
        $updatedDom = $this->getDomWithReplacements($dom);

        return $this->getHtml($updatedDom);
    }

    /**
     * @param string $html
     *
     * @return \DOMDocument
     */
    protected function getParsedDocument(string $html): DOMDocument
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return \DOMDocument
     */
    protected function getDomWithReplacements(DOMDocument $dom): DOMDocument
    {
        $replacements = [];
        $xpath = new DOMXPath($dom);
        $widgets = $xpath->query('//*[@contenteditable="false"][@data-id][@data-twig-expression][@data-template][@data-type]');

        foreach ($widgets as $widget) {
            $twigExpression = $dom->createDocumentFragment();
            $twigExpression->appendXML($widget->getAttribute('data-twig-expression'));
            $replacements[] = [$twigExpression, $widget->parentNode];
        }

        if (!$replacements) {
            return $dom;
        }

        foreach ($replacements as $replacement) {
            [$twigExpression, $widget] = $replacement;
            $widget->parentNode->replaceChild($twigExpression, $widget);
        }

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return string
     */
    protected function getHtml(DOMDocument $dom): string
    {
        return $dom->saveHTML();
    }
}
