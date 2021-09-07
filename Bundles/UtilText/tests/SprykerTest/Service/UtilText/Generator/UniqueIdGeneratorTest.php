<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText\Generator;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Generator
 * @group UniqueIdGeneratorTest
 * Add your own group annotations below this line
 */
class UniqueIdGeneratorTest extends Unit
{
    /**
     * @var string
     */
    protected const PARAM_EMPTY_PREFIX = '';
    /**
     * @var string
     */
    protected const PARAM_STRING_PREFIX = 'prefix';

    /**
     * @var \SprykerTest\Service\UtilText\UtilTextServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateUniqueIdShouldReturnString(): void
    {
        // Act
        $uniqueId = $this->tester->getService()->generateUniqueId();

        // Assert
        $this->assertIsString($uniqueId);
        $this->assertNotEmpty($uniqueId);
    }

    /**
     * @return void
     */
    public function testGenerateUniqueIdShouldReturnStringWithAdditionalEntropy(): void
    {
        // Act
        $uniqueId = $this->tester->getService()->generateUniqueId(static::PARAM_EMPTY_PREFIX, true);

        // Assert
        $this->assertIsString(stristr($uniqueId, '.'));
    }

    /**
     * @return void
     */
    public function testGenerateUniqueIdShouldReturnStringWithPrefixWhenPrefixProvidedAsParameter(): void
    {
        // Act
        $uniqueId = $this->tester->getService()->generateUniqueId(static::PARAM_STRING_PREFIX);

        // Assert
        $this->assertEquals(0, strpos($uniqueId, static::PARAM_STRING_PREFIX));
    }

    /**
     * @return void
     */
    public function testGenerateUniqueIdShouldReturnUniqueValue(): void
    {
        // Arrange
        $totalGeneratedIds = 10;

        // Act
        $uniqueIds = [];
        for ($i = 0; $i < $totalGeneratedIds; $i++) {
            $uniqueIds[] = $this->tester->getService()->generateUniqueId();
        }

        // Assert
        $this->assertEquals($totalGeneratedIds, count(array_flip($uniqueIds)));
    }
}
