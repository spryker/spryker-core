<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\DataImporter\Queue;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QueueReceiveMessageBuilder;
use Generated\Shared\DataBuilder\QueueSendMessageBuilder;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelper;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group DataImporter
 * @group Queue
 * @group QueueMessageHelperTest
 * Add your own group annotations below this line
 */
class QueueMessageHelperTest extends Unit
{
    protected const DUMMY_MESSAGE_BODY = ['dummy message body'];
    protected const DUMMY_ERROR_MESSAGE = 'dummy error message';
    protected const ERROR_MESSAGE_TYPE = 'errorMessage';

    /**
     * @var \SprykerTest\Zed\DataImport\DataImportBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DataImport\Business\DataImporter\Queue\QueueMessageHelperInterface;
     */
    protected $queueMessageHelper;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->queueMessageHelper = new QueueMessageHelper(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return void
     */
    public function testMessageBodyIsDecoded(): void
    {
        $queueReceiveMessageTransfer = $this->getQueueReceiveMessageTransfer();

        $this->assertEquals(static::DUMMY_MESSAGE_BODY, $this->queueMessageHelper->getDecodedMessageBody($queueReceiveMessageTransfer));
    }

    /**
     * @return void
     */
    public function testFailedMessageIsHandledAsErrorMessage(): void
    {
        $queueReceiveMessageTransfer = $this->getQueueReceiveMessageTransfer();
        $result = $this->queueMessageHelper->handleFailedMessage($queueReceiveMessageTransfer, static::DUMMY_ERROR_MESSAGE);
        $resultBody = $this->queueMessageHelper->getDecodedMessageBody($result);
        $this->assertNotEmpty($resultBody[static::ERROR_MESSAGE_TYPE]);
        $this->assertSame(static::DUMMY_ERROR_MESSAGE, $resultBody[static::ERROR_MESSAGE_TYPE]);
        $this->assertTrue($result->getHasError());
        $this->assertTrue($result->getReject());
        $this->assertEquals('error', $result->getRoutingKey());
    }

    /**
     * @return void
     */
    public function testSuccessMessageIsHandled(): void
    {
        $queueReceiveMessageTransfer = $this->getQueueReceiveMessageTransfer([
            'acknowledge' => false,
        ]);
        $result = $this->queueMessageHelper->handleSuccessMessage($queueReceiveMessageTransfer);
        $this->assertTrue($result->getAcknowledge());
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): DataImportToUtilEncodingServiceInterface
    {
        return new DataImportToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service()
        );
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function getQueueReceiveMessageTransfer(array $data = []): QueueReceiveMessageTransfer
    {
        $data = $data + [
            'body' => json_encode(static::DUMMY_MESSAGE_BODY),
        ];
        $queueMessageTransfer = (new QueueSendMessageBuilder($data))
            ->build();
        $data['queueMessage'] = $queueMessageTransfer;

        return (new QueueReceiveMessageBuilder($data))
            ->build();
    }
}
