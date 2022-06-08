<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Spryker\Zed\MessageBroker\Business\Exception\MessageBrokerException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Publisher
 * @group MessagePublisherTest
 * Add your own group annotations below this line
 */
class MessagePublisherTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPublishMessageThrowsExceptionWhenPassedTransferDoesNotHaveMessageAttributes(): void
    {
        // Arrange
        $messageBrokerWorkerConfigTransfer = new MessageBrokerWorkerConfigTransfer();
        $messagePublisher = $this->tester->getFactory()->createMessagePublisher();

        // Expect
        $this->expectException(MessageBrokerException::class);

        // Act
        $messagePublisher->sendMessage($messageBrokerWorkerConfigTransfer);
    }
}
