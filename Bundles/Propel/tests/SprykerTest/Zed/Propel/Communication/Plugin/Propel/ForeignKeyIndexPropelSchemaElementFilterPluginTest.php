<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Communication\Plugin\Propel;

use Codeception\Test\Unit;
use DOMDocument;
use SimpleXMLElement;
use Spryker\Zed\Propel\Communication\Plugin\Propel\ForeignKeyIndexPropelSchemaElementFilterPlugin;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Communication
 * @group Plugin
 * @group Propel
 * @group ForeignKeyIndexPropelSchemaElementFilterPluginTest
 * Add your own group annotations below this line
 */
class ForeignKeyIndexPropelSchemaElementFilterPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testFilterRemovesOnlyFirstDuplicateIndex(): void
    {
        $this->runFilterTests('spy_foo.with_index.schema.xml', 'expected.spy_foo.with_index.schema.xml');
    }

    /**
     * @return void
     */
    public function testFilterWithoutIndex(): void
    {
        $this->runFilterTests('spy_foo.without_index.schema.xml', 'expected.spy_foo.without_index.schema.xml');
    }

    /**
     * @return void
     */
    public function testFilterDoesNotRemoveUniqueIndex(): void
    {
        $this->runFilterTests('spy_foo.with_unique_index.schema.xml', 'expected.spy_foo.with_unique_index.schema.xml');
    }

    /**
     * @param string $inputFileName
     * @param string $expectedFileName
     *
     * @return void
     */
    protected function runFilterTests(string $inputFileName, string $expectedFileName): void
    {
        $schemaXmlElement = $this->createXmlElement($inputFileName);
        $schemaXmlElement = (new ForeignKeyIndexPropelSchemaElementFilterPlugin())->filter($schemaXmlElement);

        $expected = file_get_contents($this->getFixturesPathToFile($expectedFileName));

        $this->assertSame($expected, $this->formatXml($schemaXmlElement));
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     *
     * @return string
     */
    protected function formatXml(SimpleXMLElement $xmlElement): string
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xmlElement->asXML());

        $callback = function ($matches) {
            $multiplier = (strlen($matches[1]) / 2) * 4;

            return str_repeat(' ', $multiplier) . '<';
        };

        return preg_replace_callback('/^( +)</m', $callback, $dom->saveXML());
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFixturesPathToFile(string $fileName): string
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'PropelSchemaFilter',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @param string $fileName
     *
     * @return \SimpleXMLElement
     */
    protected function createXmlElement(string $fileName): SimpleXMLElement
    {
        $xmlFilePath = $this->getFixturesPathToFile($fileName);
        $schemaFile = new SplFileInfo($xmlFilePath, '', '');

        return new SimpleXMLElement($schemaFile->getContents());
    }
}
