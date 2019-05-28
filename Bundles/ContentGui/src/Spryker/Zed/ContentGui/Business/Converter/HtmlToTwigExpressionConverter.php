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
    public function replaceWidget(string $html): string
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $replacements = $this->getDomReplacements($dom);

        if (!$replacements) {
            return $html;
        }

        foreach ($replacements as $replacement) {
            [$twigExpression, $widget] = $replacement;
            $widget->parentNode->replaceChild($twigExpression, $widget);
        }

        $html = $dom->saveHTML();

        return $html;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return array
     */
    protected function getDomReplacements(DOMDocument $dom): array
    {
        $replacements = [];
        $xpath = new DOMXPath($dom);
        $widgets = $xpath->query('//*[@contenteditable="false"][@data-id][@data-twig-expression][@data-template][@data-type]');

        foreach ($widgets as $widget) {
            $twigExpression = $dom->createDocumentFragment();
            $twigExpression->appendXML($widget->getAttribute('data-twig-expression'));
            $replacements[] = [$twigExpression, $widget->parentNode];
        }

        return $replacements;
    }
}
