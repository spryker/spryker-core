<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TransactionIdMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group TransactionIdMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class TransactionIdMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsTransactionIdWhenItDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSACTION_ID => null,
        ]);
        $transactionIdMessageAttributeProviderPlugin = new TransactionIdMessageAttributeProviderPlugin();

        // Act
        $extendedMessageAttributesTransfer = $transactionIdMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNotNull($extendedMessageAttributesTransfer->getTransactionId());
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddTransactionIdWhenItAlreadyExists(): void
    {
        // Arrange
        $transactionId = Uuid::uuid4()->toString();
        $messageAttributesTransfer = $this->tester->getMessageAttributesTransfer([
            MessageAttributesTransfer::TRANSACTION_ID => $transactionId,
        ]);
        $transactionIdMessageAttributeProviderPlugin = new TransactionIdMessageAttributeProviderPlugin();

        // Act
        $extendedMessageAttributesTransfer = $transactionIdMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame($transactionId, $extendedMessageAttributesTransfer->getTransactionId());
    }
}
