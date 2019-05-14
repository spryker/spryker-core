<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use DOMDocument;
use DOMXPath;

class HtmlToShortCodeConverter implements ContentGuiConverterInterface
{
    protected const ATTRIBUTE_DATA_SHORT_CODE = 'data-short-code';

    /**
     * @param string $string
     *
     * @return string
     */
    public function convert(string $string): string
    {
        $dom = new DOMDocument();
        $dom->loadHTML($string, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $replacements = $this->getDomReplacements($dom);

        if (!$replacements) {
            return $string;
        }

        foreach ($replacements as $replacement) {
            [$shortCode, $div] = $replacement;
            $div->parentNode->replaceChild($shortCode, $div);
        }

        $string = $dom->saveHTML();

        return $string;
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
        $nodes = $xpath->query('//*[@' . static::ATTRIBUTE_DATA_SHORT_CODE . ']');

        foreach ($nodes as $node) {
            $shortCodeElement = $dom->createDocumentFragment();
            $shortCodeElement->appendXML($node->getAttribute(static::ATTRIBUTE_DATA_SHORT_CODE));
            $replacements[] = [$shortCodeElement, $node];
        }

        return $replacements;
    }
}
