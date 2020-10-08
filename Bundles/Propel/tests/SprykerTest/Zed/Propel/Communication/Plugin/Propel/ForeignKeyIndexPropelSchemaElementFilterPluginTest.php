<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Communication\Plugin\Propel;

use Codeception\Test\Unit;
use Spryker\Zed\Propel\Communication\Plugin\Propel\ForeignKeyIndexPropelSchemaElementFilterPlugin;

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
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

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
        $xmlFilePath = $this->getFixturesPathToFile($inputFileName);
        $schemaXmlElement = $this->tester->createXmlElement($xmlFilePath);
        $schemaXmlElement = (new ForeignKeyIndexPropelSchemaElementFilterPlugin())->filter($schemaXmlElement);

        $expected = file_get_contents($this->getFixturesPathToFile($expectedFileName));

        $this->assertSame($expected, $this->tester->formatXml($schemaXmlElement->asXML()));
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
            'PropelSchema',
            $fileName,
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }
}
