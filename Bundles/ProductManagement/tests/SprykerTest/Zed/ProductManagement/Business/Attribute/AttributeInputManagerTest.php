<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Codeception\Test\Unit;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use SprykerTest\Zed\ProductManagement\ProductManagementBusinessTester;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group Zed
 * @group ProductManagement
 * @group Business
 * @group Attribute
 * @group AttributeInputManagerTest
 * Add your own group annotations below this line
 */
class AttributeInputManagerTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager::INPUT_TYPE_TEXT
     *
     * @var string
     */
    protected const INPUT_TYPE_TEXT = 'text';

    /**
     * @uses \Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager::INPUT_TYPE_TEXTAREA
     *
     * @var string
     */
    protected const INPUT_TYPE_TEXTAREA = 'textarea';

    /**
     * @uses \Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager::INPUT_TYPE_SELECT2
     *
     * @var string
     */
    protected const INPUT_TYPE_SELECT2 = 'select2';

    /**
     * @uses \Spryker\Zed\ProductManagement\Business\Attribute\AttributeInputManager::VALUE_LENGTH_LIMIT
     *
     * @var int
     */
    protected const VALUE_LENGTH_LIMIT = 255;

    /**
     * @var \SprykerTest\Zed\ProductManagement\ProductManagementBusinessTester
     */
    protected ProductManagementBusinessTester $tester;

    /**
     * @dataProvider getSymfonyInputTypeDataProvider
     *
     * @param string $inputType
     * @param string $expectedSymfonyInputType
     * @param int|null $valueLength
     *
     * @return void
     */
    public function testGetSymfonyInputType(string $inputType, string $expectedSymfonyInputType, ?int $valueLength = null): void
    {
        // Arrange
        $value = $valueLength ? $this->tester->generateRandomString($valueLength) : null;

        // Act
        $symfonyInputType = (new AttributeInputManager())->getSymfonyInputType($inputType, $value);

        // Assert
        $this->assertSame($expectedSymfonyInputType, $symfonyInputType);
    }

    /**
     * @return array<string, array<string|int>>
     */
    protected function getSymfonyInputTypeDataProvider(): array
    {
        return [
            'Should return text type when the input type is empty or unknown and value length is less than the limit.' => ['', TextType::class],
            'Should return text type when the input type is `text` and value length is less than the limit.' => [static::INPUT_TYPE_TEXT, TextType::class, static::VALUE_LENGTH_LIMIT - 1],
            'Should return select2 type when the input type is `select2`.' => [static::INPUT_TYPE_SELECT2, Select2ComboBoxType::class, static::VALUE_LENGTH_LIMIT - 1],
            'Should return textarea type when the input type is `text` and value length is higher than the limit.' => [static::INPUT_TYPE_TEXT, TextareaType::class, static::VALUE_LENGTH_LIMIT + 1],
            'Should return textarea type when the input type is `select2` and value length is higher than the limit.' => [static::INPUT_TYPE_SELECT2, TextareaType::class, static::VALUE_LENGTH_LIMIT + 1],
            'Should return textarea type when the input type is `textarea`.' => [static::INPUT_TYPE_TEXTAREA, TextareaType::class],

        ];
    }
}
