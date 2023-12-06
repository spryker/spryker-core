<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilText;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilText
 * @group Facade
 * @group UtilTextFacadeTest
 * Add your own group annotations below this line
 */
class UtilTextFacadeTest extends Unit
{
    /**
     * @var \Spryker\Service\UtilText\UtilTextService
     */
    protected UtilTextService $utilTextFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->utilTextFacade = new UtilTextService();
    }

    /**
     * @return void
     */
    public function testGenerateSlug(): void
    {
        // Arrange
        $expectedSlug = 'a-value-to-slug-8-times';

        // Act
        $slug = $this->utilTextFacade->generateSlug('A #value#, [to] Slug 8 times.');

        // Assert
        $this->assertSame($expectedSlug, $slug);
    }

    /**
     * @return void
     */
    public function testGenerateSlugWithNonUTF8Char(): void
    {
        // Arrange
        $expectedSlug = 'test-slug';

        // Act
        $slug = $this->utilTextFacade->generateSlug('test ​​slug');

        // Assert
        $this->assertSame($expectedSlug, $slug);
    }

    /**
     * @return void
     */
    public function testGenerateRandomByteStringWillGenerateByteStringOfExpectedLength(): void
    {
        //Arrange
        $length = 64;

        //Act
        $string = $this->utilTextFacade->generateRandomByteString($length);

        //Assert
        $this->assertSame($length, strlen($string), 'String length did not match expected value.');
    }
}
