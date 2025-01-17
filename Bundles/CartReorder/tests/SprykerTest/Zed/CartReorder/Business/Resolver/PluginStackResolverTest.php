<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartReorder\Business\Resolver;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartReorder\Business\Resolver\PluginStackResolver;
use Spryker\Zed\CartReorder\Business\Resolver\PluginStackResolverInterface;
use SprykerTest\Zed\CartReorder\CartReorderBusinessTester;
use stdClass;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartReorder
 * @group Business
 * @group Resolver
 * @group PluginStackResolverTest
 * Add your own group annotations below this line
 */
class PluginStackResolverTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_QUOTE_PROCESS_FLOW_NAME = 'test-quote-process-flow';

    /**
     * @uses \Spryker\Zed\CartReorder\CartReorderConfig::DEFAULT_QUOTE_PROCESS_FLOW
     *
     * @var string
     */
    protected const DEFAULT_QUOTE_PROCESS_FLOW = 'default';

    /**
     * @var \SprykerTest\Zed\CartReorder\CartReorderBusinessTester
     */
    protected CartReorderBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsPluginStackForProvidedQuoteFlowProcessName(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withQuoteProcessFlow([QuoteProcessFlowTransfer::NAME => static::TEST_QUOTE_PROCESS_FLOW_NAME])
            ->build();
        $object1 = new stdClass();
        $object2 = new stdClass();

        $pluginsStack = [
            static::DEFAULT_QUOTE_PROCESS_FLOW => [$object1],
            static::TEST_QUOTE_PROCESS_FLOW_NAME => [$object2],
        ];

        // Act
        $resultPluginStack = $this->getPluginStackResolver()->resolvePluginStackByQuoteProcessFlowName($quoteTransfer, $pluginsStack);

        // Assert
        $this->assertCount(1, $resultPluginStack);
        $this->assertSame(spl_object_id($object2), spl_object_id($resultPluginStack[0]));
    }

    /**
     * @return void
     */
    public function testReturnsDefaultPluginStackWhenPluginStackForQuoteProcessFlowNameIsNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withQuoteProcessFlow([QuoteProcessFlowTransfer::NAME => static::TEST_QUOTE_PROCESS_FLOW_NAME])
            ->build();
        $object1 = new stdClass();
        $object2 = new stdClass();

        $pluginsStack = [
            static::DEFAULT_QUOTE_PROCESS_FLOW => [$object1],
            'another-quote-process-flow' => [$object2],
        ];

        // Act
        $resultPluginStack = $this->getPluginStackResolver()->resolvePluginStackByQuoteProcessFlowName($quoteTransfer, $pluginsStack);

        // Assert
        $this->assertCount(1, $resultPluginStack);
        $this->assertSame(spl_object_id($object1), spl_object_id($resultPluginStack[0]));
    }

    /**
     * @return void
     */
    public function testReturnsDefaultPluginStackWhenQuoteProcessFlowNameIsNotProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::QUOTE_PROCESS_FLOW => null]))->build();
        $object1 = new stdClass();
        $object2 = new stdClass();

        $pluginsStack = [
            static::DEFAULT_QUOTE_PROCESS_FLOW => [$object1],
            static::TEST_QUOTE_PROCESS_FLOW_NAME => [$object2],
        ];

        // Act
        $resultPluginStack = $this->getPluginStackResolver()->resolvePluginStackByQuoteProcessFlowName($quoteTransfer, $pluginsStack);

        // Assert
        $this->assertCount(1, $resultPluginStack);
        $this->assertSame(spl_object_id($object1), spl_object_id($resultPluginStack[0]));
    }

    /**
     * @return void
     */
    public function testReturnsAllPluginsWhenPluginStackIsSingleDimensional(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withQuoteProcessFlow([QuoteProcessFlowTransfer::NAME => static::TEST_QUOTE_PROCESS_FLOW_NAME])
            ->build();
        $object1 = new stdClass();
        $object2 = new stdClass();

        $pluginsStack = [$object1, $object2];

        // Act
        $resultPluginStack = $this->getPluginStackResolver()->resolvePluginStackByQuoteProcessFlowName($quoteTransfer, $pluginsStack);

        // Assert
        $this->assertCount(2, $resultPluginStack);
        $this->assertSame(spl_object_id($object1), spl_object_id($resultPluginStack[0]));
        $this->assertSame(spl_object_id($object2), spl_object_id($resultPluginStack[1]));
    }

    /**
     * @return \Spryker\Zed\CartReorder\Business\Resolver\PluginStackResolverInterface
     */
    protected function getPluginStackResolver(): PluginStackResolverInterface
    {
        return new PluginStackResolver($this->tester->getModuleConfig());
    }
}
