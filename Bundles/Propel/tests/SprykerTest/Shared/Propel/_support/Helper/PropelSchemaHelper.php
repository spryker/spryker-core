<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Propel\Helper;

use Codeception\Module;
use DOMDocument;
use SimpleXMLElement;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaHelper extends Module
{
    /**
     * @param string $xml
     *
     * @return string
     */
    public function formatXml(string $xml): string
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);

        return preg_replace_callback('/^( +)</m', [$this, 'xmlSpacesDoublerFilter'], $dom->saveXML());
    }

    /**
     * @param string $xmlFilePath
     *
     * @return \SimpleXMLElement
     */
    public function createXmlElement(string $xmlFilePath): SimpleXMLElement
    {
        $schemaFile = new SplFileInfo($xmlFilePath, '', '');

        return new SimpleXMLElement($schemaFile->getContents());
    }

    /**
     * @param array $matches
     *
     * @return string
     */
    protected function xmlSpacesDoublerFilter(array $matches): string
    {
        $multiplier = strlen($matches[1]) * 2;

        return str_repeat(' ', $multiplier) . '<';
    }
}
