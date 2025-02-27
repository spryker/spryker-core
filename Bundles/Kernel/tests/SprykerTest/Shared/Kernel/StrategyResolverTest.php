<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use Codeception\Test\Unit;
use InvalidArgumentException;
use Spryker\Shared\Kernel\StrategyResolver;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group StrategyResolverTest
 * Add your own group annotations below this line
 */
class StrategyResolverTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CONTEXT_1 = 'context-1';

    /**
     * @var string
     */
    protected const TEST_CONTEXT_2 = 'context-2';

    /**
     * @return void
     */
    public function testGetResolvesCorrectContext(): void
    {
        // Arrange
        $object1 = new stdClass();
        $object2 = new stdClass();
        $strategyResolver = new StrategyResolver(
            [
                static::TEST_CONTEXT_1 => [$object1],
                static::TEST_CONTEXT_2 => [$object2],
            ],
        );

        // Act
        $strategy1 = $strategyResolver->get(static::TEST_CONTEXT_1);
        $strategy2 = $strategyResolver->get(static::TEST_CONTEXT_2);

        // Assert
        $this->assertCount(1, $strategy1);
        $this->assertCount(1, $strategy2);
        $this->assertSame(spl_object_id($object1), spl_object_id($strategy1[0]));
        $this->assertSame(spl_object_id($object2), spl_object_id($strategy2[0]));
    }

    /**
     * @return void
     */
    public function testGetResolvesFallbackContext(): void
    {
        // Arrange
        $object1 = new stdClass();
        $object2 = new stdClass();
        $strategyResolver = new StrategyResolver(
            [
                static::TEST_CONTEXT_1 => [$object1, $object2],
            ],
            static::TEST_CONTEXT_1,
        );

        // Act
        $strategy1 = $strategyResolver->get(static::TEST_CONTEXT_2);
        $strategy2 = $strategyResolver->get(null);

        // Assert
        $this->assertCount(2, $strategy1);
        $this->assertCount(2, $strategy2);
    }

    /**
     * @return void
     */
    public function testGetThrowsErrorIfNoFallback(): void
    {
        // Arrange
        $object1 = new stdClass();
        $object2 = new stdClass();
        $strategyResolver = new StrategyResolver(
            [
                static::TEST_CONTEXT_1 => [$object1, $object2],
            ],
        );

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $strategyResolver->get(static::TEST_CONTEXT_2);
    }

    /**
     * @return void
     */
    public function testGetThrowsErrorWithoutContextIfNoFallback(): void
    {
        // Arrange
        $object1 = new stdClass();
        $object2 = new stdClass();
        $strategyResolver = new StrategyResolver(
            [
                static::TEST_CONTEXT_1 => [$object1, $object2],
            ],
        );

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $strategyResolver->get(null);
    }
}
