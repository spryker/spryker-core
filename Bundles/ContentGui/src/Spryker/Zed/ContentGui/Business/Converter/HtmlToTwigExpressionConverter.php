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
        if (mb_substr_count($html, 'data-twig-expression') > 10000) {
            return $html;
        }

        // Libxml requires a root node and <html> is treating the first element, so libxml finds as the root node.
        $this->domDocument->loadHTML("<html>$html</html>", LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $replaceableNodes = $this->getReplaceableNodes();

        if (!$replaceableNodes) {
            return $html;
        }

        $this->replaceNodes($replaceableNodes);

        return str_replace(['<html>', '</html>'], '', $this->domDocument->saveHTML());
    }

    /**
     * @return array
     */
    protected function getReplaceableNodes(): array
    {
        $replacements = [];
        $domXpath = $this->createDOMXPath();
        $widgets = $domXpath->query('//*[@contenteditable="false"][@data-id][@data-twig-expression][@data-template][@data-type]');

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
}
