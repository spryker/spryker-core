<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business\Message;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Shared\Synchronization\SynchronizationConfig;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageHelper;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Business
 * @group Message
 * @group QueueMessageHelperTest
 * Add your own group annotations below this line
 */
class QueueMessageHelperTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ERROR_MESSAGE = 'test error message';

    /**
     * @return void
     */
    public function testMarkMessageAsFailed(): void
    {
        $utilEncodingService = $this->createSynchronizationToUtilEncodingService();
        $queueMessageHelper = new QueueMessageHelper($utilEncodingService);

        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueReceiveMessageTransfer = new QueueReceiveMessageTransfer();
        $queueReceiveMessageTransfer->setQueueMessage($queueSendMessageTransfer);

        /** @var \Generated\Shared\Transfer\QueueReceiveMessageTransfer $resultQueueReceiveMessageTransfer */
        $resultQueueReceiveMessageTransfer = $queueMessageHelper->markMessageAsFailed(
            $queueReceiveMessageTransfer,
            static::TEST_ERROR_MESSAGE,
        );

        $this->assertFalse($resultQueueReceiveMessageTransfer->getAcknowledge());
        $this->assertTrue($resultQueueReceiveMessageTransfer->getReject());
        $this->assertTrue($resultQueueReceiveMessageTransfer->getHasError());
        $this->assertSame($resultQueueReceiveMessageTransfer->getRoutingKey(), SynchronizationConfig::MESSAGE_ROUTING_KEY_ERROR);

        $queueMessageBody = json_decode($resultQueueReceiveMessageTransfer->getQueueMessage()->getBody(), true);
        $this->assertSame(static::TEST_ERROR_MESSAGE, $queueMessageBody['errorMessage']);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected function createSynchronizationToUtilEncodingService(): SynchronizationToUtilEncodingServiceInterface
    {
        $utilEncodingService = $this->getMockBuilder(SynchronizationToUtilEncodingServiceInterface::class)->getMock();

        $utilEncodingService->method('decodeJson')->willReturnCallback(function ($jsonValue, $assoc, $depth = null, $options = null) {
            return json_decode($jsonValue, $assoc);
        });
        $utilEncodingService->method('encodeJson')->willReturnCallback(function ($array) {
            return json_encode($array);
        });

        return $utilEncodingService;
    }
}
