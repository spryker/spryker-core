<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Communication\Plugin\ProductAttribute;

use Codeception\Test\Unit;
use Spryker\Zed\ProductAttribute\Communication\Plugin\ProductAttribute\MultiSelectProductAttributeDataFormatterPlugin;
use SprykerTest\Zed\ProductAttribute\ProductAttributeCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAttribute
 * @group Communication
 * @group Plugin
 * @group ProductAttribute
 * @group MultiSelectProductAttributeDataFormatterPluginTest
 * Add your own group annotations below this line
 */
class MultiSelectProductAttributeDataFormatterPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAttribute\ProductAttributeCommunicationTester
     */
    protected ProductAttributeCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldFormatMultiSelectAttributes(): void
    {
        // Arrange
        $multiSelectProductAttributeDataFormatterPlugin = new MultiSelectProductAttributeDataFormatterPlugin();

        // Act
        $formattedAttributes = $multiSelectProductAttributeDataFormatterPlugin->format(
            $this->prepareDummyAttributes(),
            $this->prepareDummyFormattedAttributes(),
        );

        // Assert
        $this->assertSame(['Windows 10'], $formattedAttributes['en_US']['pc_operating_system']);
        $this->assertSame('Red', $formattedAttributes['en_US']['color']);

        $this->assertSame(['Windows 10', 'Windows 11'], $formattedAttributes['de_DE']['pc_operating_system']);
        $this->assertSame('White', $formattedAttributes['de_DE']['color']);

        $this->assertSame(['Windows 10', 'Windows 11', 'Ubuntu'], $formattedAttributes['_']['pc_operating_system']);
        $this->assertSame('Orange', $formattedAttributes['_']['color']);
    }

    /**
     * @return void
     */
    public function testShouldIgnoreFormattingForAttributesWithoutInputType(): void
    {
        // Arrange
        $multiSelectProductAttributeDataFormatterPlugin = new MultiSelectProductAttributeDataFormatterPlugin();

        // Act
        $formattedAttributes = $multiSelectProductAttributeDataFormatterPlugin->format(
            [
                [
                    'key' => 'pc_operating_system',
                    'id' => '116',
                    'locale_code' => '_',
                    'value' => 'Windows 10, Windows 11',
                ],
            ],
            [
                '_' => [
                    'pc_operating_system' => 'Windows 10, Windows 11',
                ],
            ],
        );

        // Assert
        $this->assertSame('Windows 10, Windows 11', $formattedAttributes['_']['pc_operating_system']);
    }

    /**
     * @return void
     */
    public function testShouldIgnoreFormattingForAttributesWithoutMultiSelectInputType(): void
    {
        // Arrange
        $multiSelectProductAttributeDataFormatterPlugin = new MultiSelectProductAttributeDataFormatterPlugin();

        // Act
        $formattedAttributes = $multiSelectProductAttributeDataFormatterPlugin->format(
            [
                [
                    'key' => 'pc_operating_system',
                    'id' => '116',
                    'locale_code' => '_',
                    'value' => 'Windows 10, Windows 11',
                    'input_type' => 'select',
                ],
            ],
            [
                '_' => [
                    'pc_operating_system' => 'Windows 10, Windows 11',
                ],
            ],
        );

        // Assert
        $this->assertSame('Windows 10, Windows 11', $formattedAttributes['_']['pc_operating_system']);
    }

    /**
     * @return void
     */
    public function testShouldIgnoreFormattingForAttributesWithoutValue(): void
    {
        // Arrange
        $multiSelectProductAttributeDataFormatterPlugin = new MultiSelectProductAttributeDataFormatterPlugin();

        // Act
        $formattedAttributes = $multiSelectProductAttributeDataFormatterPlugin->format(
            [
                [
                    'key' => 'pc_operating_system',
                    'id' => '116',
                    'locale_code' => '_',
                    'value' => '',
                    'input_type' => 'multiselect',
                ],
            ],
            [
                '_' => [],
            ],
        );

        // Assert
        $this->assertEmpty($formattedAttributes['_']);
    }

    /**
     * @return void
     */
    public function testShouldTrimMultiSelectAttributes(): void
    {
        // Arrange
        $multiSelectProductAttributeDataFormatterPlugin = new MultiSelectProductAttributeDataFormatterPlugin();

        // Act
        $formattedAttributes = $multiSelectProductAttributeDataFormatterPlugin->format(
            [
                [
                    'key' => 'pc_operating_system',
                    'id' => '116',
                    'locale_code' => '_',
                    'value' => 'Windows 10, Windows 11',
                    'input_type' => 'multiselect',
                ],
            ],
            [
                '_' => [
                    'pc_operating_system' => ' Windows 10, Windows 11 ',
                ],
            ],
        );

        // Assert
        $this->assertSame(['Windows 10', 'Windows 11'], $formattedAttributes['_']['pc_operating_system']);
    }

    /**
     * @return array<array<string, string>>
     */
    protected function prepareDummyAttributes(): array
    {
        return [
            [
                'key' => 'color',
                'id' => '87',
                'locale_code' => 'en_US',
                'value' => 'Red',
                'input_type' => 'text',
            ],
            [
                'key' => 'color',
                'id' => '87',
                'locale_code' => 'de_DE',
                'value' => 'White',
                'input_type' => 'text',
            ],
            [
                'key' => 'color',
                'id' => '87',
                'locale_code' => '_',
                'value' => 'Orange',
                'input_type' => 'text',
            ],
            [
                'key' => 'pc_operating_system',
                'id' => '116',
                'locale_code' => 'en_US',
                'value' => 'Windows 10',
                'input_type' => 'multiselect',
            ],
            [
                'key' => 'pc_operating_system',
                'id' => '116',
                'locale_code' => 'de_DE',
                'value' => 'Windows 10, Windows 11',
                'input_type' => 'multiselect',
            ],
            [
                'key' => 'pc_operating_system',
                'id' => '116',
                'locale_code' => '_',
                'value' => 'Windows 10, Windows 11, Ubuntu',
                'input_type' => 'multiselect',
            ],
        ];
    }

    /**
     * @return array<array<string, string>>
     */
    protected function prepareDummyFormattedAttributes(): array
    {
        return [
            'en_US' => [
                'color' => 'Red',
                'pc_operating_system' => 'Windows 10',
            ],
            'de_DE' => [
                'color' => 'White',
                'pc_operating_system' => 'Windows 10, Windows 11',
            ],
            '_' => [
                'color' => 'Orange',
                'pc_operating_system' => 'Windows 10, Windows 11, Ubuntu',
            ],
        ];
    }
}
