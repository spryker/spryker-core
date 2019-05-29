<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter;

use DOMDocument;
use DOMDocumentFragment;
use DOMNode;
use DOMXPath;

class HtmlToTwigExpressionConverter implements HtmlConverterInterface
{
    /**
     * @var \DOMDocument
     */
    protected $domDocument;

    /**
     * @param \DOMDocument $domDocument
     */
    public function __construct(DOMDocument $domDocument)
    {
        $this->domDocument = $domDocument;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function convertHtmlToTwigExpression(string $html): string
    {
        $this->domDocument->loadHTML($html, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $replacements = $this->getReplacements();

        if (!$replacements) {
            return $html;
        }

        foreach ($replacements as $replacement) {
            $parentNode = $replacement['oldNode']->parentNode;
            $parentNode->replaceChild($replacement['newNode'], $replacement['oldNode']);
        }

        return $this->domDocument->saveHTML();
    }

    /**
     * @return array
     */
    protected function getReplacements(): array
    {
        $replacements = [];
        $domXpath = $this->createDOMXPath();
        $widgets = $domXpath->query('//*[@contenteditable="false"][@data-id][@data-twig-expression][@data-template][@data-type]');

        foreach ($widgets as $widget) {
            $twigExpression = $this->domDocument->createDocumentFragment();
            $twigExpression->appendXML($widget->getAttribute('data-twig-expression'));
            $replacements[] = $this->addReplacement($twigExpression, $widget->parentNode);
        }

        return $replacements;
    }

    /**
     * @param \DOMDocumentFragment $twigExpression
     * @param \DOMNode $parentNode
     *
     * @return array
     */
    protected function addReplacement(DOMDocumentFragment $twigExpression, DOMNode $parentNode): array
    {
        return ['newNode' => $twigExpression, 'oldNode' => $parentNode];
    }

    /**
     * @return \DOMXPath
     */
    protected function createDOMXPath(): DOMXPath
    {
        return new DOMXPath($this->domDocument);
    }
}
