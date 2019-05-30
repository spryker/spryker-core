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
        // Libxml requires a root node and <html> is treating the first element, so libxml finds as the root node.
        $this->domDocument->loadHTML("<html>$html</html>", LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $replacements = $this->getReplacements();

        if (!$replacements) {
            return $html;
        }

        foreach ($replacements as $replacement) {
            $parentNode = $replacement['oldNode']->parentNode;
            $parentNode->insertBefore($replacement['newNode'], $replacement['oldNode']);
            $parentNode->removeChild($replacement['oldNode']);
        }

        $html = str_replace(['<html>', '</html>'], '', $this->domDocument->saveHTML());

        return $html;
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
            $twigExpression = $this->domDocument->createTextNode($widget->getAttribute('data-twig-expression'));
            $replacements[] = $this->addReplacement($twigExpression, $widget->parentNode);
        }

        return $replacements;
    }

    /**
     * @param \DOMText $twigExpression
     * @param \DOMNode $parentNode
     *
     * @return \DOMNode[]
     */
    protected function addReplacement(DOMText $twigExpression, DOMNode $parentNode): array
    {
        return ['oldNode' => $parentNode, 'newNode' => $twigExpression];
    }

    /**
     * @return \DOMXPath
     */
    protected function createDOMXPath(): DOMXPath
    {
        return new DOMXPath($this->domDocument);
    }
}
