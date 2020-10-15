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
    protected const SCHEMA_WITH_INDEX = 'spy_foo.with_index.schema.xml';
    protected const EXPECTED_SCHEMA_WITH_INDEX = 'expected.spy_foo.with_index.schema.xml';
    protected const SCHEMA_WITHOUT_INDEX = 'spy_foo.without_index.schema.xml';
    protected const EXPECTED_SCHEMA_WITHOUT_INDEX = 'expected.spy_foo.without_index.schema.xml';
    protected const SCHEMA_WITH_UNIQUE_INDEX = 'spy_foo.with_unique_index.schema.xml';
    protected const EXPECTED_SCHEMA_WITH_UNIQUE_INDEX = 'expected.spy_foo.with_unique_index.schema.xml';

    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider filterDataProvider
     *
     * @param string $inputFileName
     * @param string $expectedFileName
     *
     * @return void
     */
    public function testFilterShouldFilterSchema(string $inputFileName, string $expectedFileName): void
    {
        // Arrange
        $xmlFilePath = $this->getFixturesPathToFile($inputFileName);
        $schemaXmlElement = $this->tester->createXmlElement($xmlFilePath);
        $expected = file_get_contents($this->getFixturesPathToFile($expectedFileName));

        // Act
        $schemaXmlElement = (new ForeignKeyIndexPropelSchemaElementFilterPlugin())->filter($schemaXmlElement);

        // Assert
        $this->assertSame($expected, $this->tester->formatXml($schemaXmlElement->asXML()));
    }

    /**
     * @return array
     */
    public function filterDataProvider(): array
    {
        return [
            [static::SCHEMA_WITH_INDEX, static::EXPECTED_SCHEMA_WITH_INDEX],
            [static::SCHEMA_WITHOUT_INDEX, static::EXPECTED_SCHEMA_WITHOUT_INDEX],
            [static::SCHEMA_WITH_UNIQUE_INDEX, static::EXPECTED_SCHEMA_WITH_UNIQUE_INDEX],
        ];
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
