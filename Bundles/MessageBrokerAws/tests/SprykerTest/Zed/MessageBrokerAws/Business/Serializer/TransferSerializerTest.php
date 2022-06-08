<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Business\Serializer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerAwsTestMessageWithoutMessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use stdClass;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBrokerAws
 * @group Business
 * @group Serializer
 * @group TransferSerializerTest
 * Add your own group annotations below this line
 */
class TransferSerializerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MessageBrokerAws\MessageBrokerAwsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDecodeThrowsExceptionWhenBodyIsMissing(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Encoded envelope should have a "body".');

        // Act
        $transferSerializer->decode([]);
    }

    /**
     * @return void
     */
    public function testDecodeThrowsExceptionWhenHeaderIsMissing(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Encoded envelope should have some "headers".');

        // Act
        $transferSerializer->decode(['body' => 'CatFace']);
    }

    /**
     * @return void
     */
    public function testDecodeThrowsExceptionWhenHeaderDoesNotHaveATransferName(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Encoded envelope does not have a "transferName" header. The "transferName" is referring to a Transfer class that is used to unserialize the message data.');

        // Act
        $transferSerializer->decode(['body' => 'CatFace', 'headers' => ['foo']]);
    }

    /**
     * @return void
     */
    public function testDecodeThrowsExceptionWhenClassByTransferNameCouldNotBeLoaded(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Could not find the "\Generated\Shared\Transfer\CatFaceTransfer" transfer object to unserialize the data.');

        // Act
        $transferSerializer->decode(['body' => 'CatFace', 'headers' => ['transferName' => 'CatFace']]);
    }

    /**
     * @return void
     */
    public function testDecodeThrowsExceptionWhenMessageBodyCouldNotBeDeserialized(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Could not decode message: Syntax error');

        // Act
        $transferSerializer->decode(['body' => 'CatFace', 'headers' => ['transferName' => 'MessageBrokerTestMessage']]);
    }

    /**
     * @return void
     */
    public function testDecodeReturnsEnvelopeWhenMessageIsSuccessfullyDeserialized(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();

        // Act
        $envelope = $transferSerializer->decode(
            [
                'body' => '{"key": "value"}',
                'headers' => [
                    'transferName' => 'MessageBrokerTestMessage',
                    'publisher' => 'publisher',
                ],
            ],
        );

        // Assert
        $this->assertInstanceOf(Envelope::class, $envelope);
    }

    /**
     * @return void
     */
    public function testEncodeReturnsThrowsExceptionWhenMessageIsNotAInstanceOfAbstractTransfer(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();
        $envelope = new Envelope(new stdClass());

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Could not decode message, expected type of "Spryker\Shared\Kernel\Transfer\AbstractTransfer" but got "object".');

        // Act
        $transferSerializer->encode($envelope);
    }

    /**
     * @return void
     */
    public function testEncodeReturnsThrowsExceptionWhenMessageTransferDoesNotHaveMessageAttributesAttribute(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();
        $envelope = new Envelope(new MessageBrokerAwsTestMessageWithoutMessageAttributesTransfer());

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Could not decode message, expected to have a method "getMessageAttributes()" but it was not found in "Generated\Shared\Transfer\MessageBrokerAwsTestMessageWithoutMessageAttributesTransfer".');

        // Act
        $transferSerializer->encode($envelope);
    }

    /**
     * @return void
     */
    public function testEncodeReturnsThrowsExceptionWhenMessageTransferDoesNotHaveMessageAttributesSet(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();
        $envelope = new Envelope(new MessageBrokerTestMessageTransfer());

        // Expect
        $this->expectException(MessageDecodingFailedException::class);
        $this->expectExceptionMessage('Could not decode message, expected to have a Transfer object "Generated\Shared\Transfer\MessageAttributesTransfer" inside your "Generated\Shared\Transfer\MessageBrokerTestMessageTransfer" message transfer but it is empty.');

        // Act
        $transferSerializer->encode($envelope);
    }

    /**
     * @return void
     */
    public function testEncodeReturnsArrayWithEncodedData(): void
    {
        // Arrange
        $transferSerializer = $this->tester->getFactory()->createSerializer();
        $messageBrokerTestMessageTransfer = new MessageBrokerTestMessageTransfer();
        $messageAttributes = new MessageAttributesTransfer();
        $messageAttributes->setTransferName('MessageBrokerTestMessage');
        $messageBrokerTestMessageTransfer->setMessageAttributes($messageAttributes);

        $envelope = new Envelope($messageBrokerTestMessageTransfer);

        // Act
        $decodedData = $transferSerializer->encode($envelope);

        // Assert
        $this->assertArrayHasKey('body', $decodedData);
        $this->assertArrayHasKey('bodyRaw', $decodedData);
        $this->assertArrayHasKey('headers', $decodedData);
    }
}
