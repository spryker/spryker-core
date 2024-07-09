<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Log\Plugin\Log;

use Codeception\Test\Unit;
use Monolog\Handler\BufferHandler;
use Spryker\Shared\Log\Exception\InvalidLogRecordTagsTypeException;
use Spryker\Shared\Log\Handler\TagFilterBufferedStreamHandler;
use Spryker\Yves\Log\LogConfig;
use Spryker\Yves\Log\LogFactory;
use Spryker\Yves\Log\Plugin\Log\AuditLogTagFilterBufferedStreamHandlerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Log
 * @group Plugin
 * @group Log
 * @group AuditLogTagFilterBufferedStreamHandlerPluginTest
 * Add your own group annotations below this line
 */
class AuditLogTagFilterBufferedStreamHandlerPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testHandleFiltersOutDisallowedTags(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(LogConfig::class)->getMock();
        $configMock->method('getAuditLogTagDisallowList')->willReturn(['test_tag']);
        $auditLogTagFilterBufferedStreamHandlerPlugin = new AuditLogTagFilterBufferedStreamHandlerPlugin();
        $auditLogTagFilterBufferedStreamHandlerPlugin->setConfig($configMock);

        // Act
        $result = $auditLogTagFilterBufferedStreamHandlerPlugin->handle(['context' => ['tags' => ['test_tag']]]);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHandleThrowsAnExceptionWhenContextTagsKeyIsNotAnArray(): void
    {
        // Assert
        $this->expectException(InvalidLogRecordTagsTypeException::class);

        // Arrange
        $auditLogTagFilterBufferedStreamHandlerPlugin = new AuditLogTagFilterBufferedStreamHandlerPlugin();

        // Act
        $auditLogTagFilterBufferedStreamHandlerPlugin->handle(['context' => ['tags' => 'test_tag1, test_tag2']]);
    }

    /**
     * @return void
     */
    public function testHandleWritesMessageToLogFileWhenContextTagIsNotSet(): void
    {
        // Arrange
        $auditLogTagFilterBufferedStreamHandlerPlugin = $this->getAuditLogTagFilterBufferedStreamHandlerPlugin();

        // Act
        $auditLogTagFilterBufferedStreamHandlerPlugin->handle(['context' => []]);
    }

    /**
     * @return void
     */
    public function testHandleWritesMessageToLogFileWhenContextTagIsSet(): void
    {
        // Arrange
        $auditLogTagFilterBufferedStreamHandlerPlugin = $this->getAuditLogTagFilterBufferedStreamHandlerPlugin();

        // Act
        $auditLogTagFilterBufferedStreamHandlerPlugin->handle(['context' => ['tags' => ['test_tag']]]);
    }

    /**
     * @return \Spryker\Yves\Log\Plugin\Log\AuditLogTagFilterBufferedStreamHandlerPlugin
     */
    protected function getAuditLogTagFilterBufferedStreamHandlerPlugin(): AuditLogTagFilterBufferedStreamHandlerPlugin
    {
        $bufferHandlerMock = $this->getMockBuilder(BufferHandler::class)->disableOriginalConstructor()->getMock();
        $bufferHandlerMock->method('handle')->willReturn(true);
        $bufferHandlerMock->expects($this->once())->method('handle');

        $tagFilterBufferedStreamHandler = new TagFilterBufferedStreamHandler($bufferHandlerMock, []);

        $factoryMock = $this->getMockBuilder(LogFactory::class)->getMock();
        $factoryMock->method('createBufferedStreamHandler')->willReturn($bufferHandlerMock);
        $factoryMock->method('createTagFilterBufferedStreamHandler')->willReturn($tagFilterBufferedStreamHandler);

        $auditLogTagFilterBufferedStreamHandlerPlugin = new AuditLogTagFilterBufferedStreamHandlerPlugin();
        $auditLogTagFilterBufferedStreamHandlerPlugin->setFactory($factoryMock);

        return $auditLogTagFilterBufferedStreamHandlerPlugin;
    }
}
