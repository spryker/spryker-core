<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Log\Plugin\Handler;

use Codeception\Test\Unit;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Spryker\Yves\Log\Plugin\Handler\ExceptionStreamHandlerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Log
 * @group Plugin
 * @group Handler
 * @group ExceptionStreamHandlerPluginTest
 * Add your own group annotations below this line
 */
class ExceptionStreamHandlerPluginTest extends Unit
{
    public const FILENAME = 'exception.log';

    /**
     * @var \SprykerTest\Yves\Log\LogPluginTester
     */
    protected $tester;

    /**
     * @return \Spryker\Yves\Log\Plugin\Handler\ExceptionStreamHandlerPlugin
     */
    protected function getExceptionStreamHandlerPlugin(): ExceptionStreamHandlerPlugin
    {
        $this->tester->mockConfigMethod('getExceptionLogDestinationPath', function () {
            return $this->tester->getPathToLogFile(static::FILENAME);
        });

        $handler = new ExceptionStreamHandlerPlugin();
        $handler->setFactory($this->tester->getFactory());

        return $handler;
    }

    /**
     * @return void
     */
    public function testIsHandlingReturnsTrueIfLogLevelIsError(): void
    {
        $record = ['level' => Logger::ERROR];

        $this->assertTrue($this->getExceptionStreamHandlerPlugin()->isHandling($record));
    }

    /**
     * @return void
     */
    public function testIsHandlingReturnsFalseIfLogLevelIsLowerThanError(): void
    {
        $record = ['level' => Logger::DEBUG];

        $this->assertFalse($this->getExceptionStreamHandlerPlugin()->isHandling($record));
    }

    /**
     * @return void
     */
    public function testHandleWritesMessageToLogFile(): void
    {
        $record = ['level' => Logger::ERROR, 'extra' => [], 'context' => [], 'message' => 'Test exception logs'];
        $this->getExceptionStreamHandlerPlugin()->handle($record);

        $this->tester->assertLogFileContains(static::FILENAME, 'Test exception logs');
    }

    /**
     * @return void
     */
    public function testHandleBatchWritesMessagesToLogFile(): void
    {
        $records = [
            ['level' => Logger::ERROR, 'extra' => [], 'context' => [], 'message' => 'Test exception logs'],
            ['level' => Logger::ERROR, 'extra' => [], 'context' => [], 'message' => 'Test exception logs 2'],
        ];

        $this->getExceptionStreamHandlerPlugin()->handleBatch($records);

        $this->tester->assertLogFileContains(static::FILENAME, 'Test exception logs');
        $this->tester->assertLogFileContains(static::FILENAME, 'Test exception logs 2');
    }

    /**
     * @return void
     */
    public function testPushProcessorReturnsProcessableHandlerInterface(): void
    {
        $processableInterface = $this->getExceptionStreamHandlerPlugin()->pushProcessor(function () {
        });

        $this->assertInstanceOf(HandlerInterface::class, $processableInterface);
    }

    /**
     * @return void
     */
    public function testPopProcessorReturnsAddedProcessor(): void
    {
        $processor = function () {
        };
        $handler = $this->getExceptionStreamHandlerPlugin();
        $handler->pushProcessor($processor);

        $this->assertSame($processor, $handler->popProcessor());
    }

    /**
     * @return void
     */
    public function testSetFormatterReturnsFormattableHandlerInterface(): void
    {
        $formatterMock = $this->getFormatterMock();
        $formattableInterface = $this->getExceptionStreamHandlerPlugin()->setFormatter($formatterMock);

        $this->assertInstanceOf(HandlerInterface::class, $formattableInterface);
    }

    /**
     * @return \Monolog\Formatter\FormatterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFormatterMock(): FormatterInterface
    {
        return $this->getMockBuilder(FormatterInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testGetFormatterReturnsAddedFormatter(): void
    {
        $formatterMock = $this->getFormatterMock();
        $handler = $this->getExceptionStreamHandlerPlugin();
        $handler->setFormatter($formatterMock);

        $this->assertInstanceOf(FormatterInterface::class, $handler->getFormatter());
    }
}
