<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws\Business\Config;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MessageBrokerAws\Business\Config\JsonToArrayConfigFormatter;
use Spryker\Zed\MessageBrokerAws\Business\Exception\ConfigDecodingFailedException;
use Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBrokerAws
 * @group Business
 * @group Config
 * @group JsonToArrayConfigFormatterTest
 * Add your own group annotations below this line
 */
class JsonToArrayConfigFormatterTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'foo';

    /**
     * @return void
     */
    public function testFormatReturnsUnchangedConfigurationWhenSimpleConfiguration(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter(
            $this->getMockStoreFacadeDefaultStore(),
            $this->getUtilEncodingMock(),
        );

        // Act
        $formatted = $jsonToArrayConfigFormatter->format('{"foo": "bar"}');

        // Assert
        $this->assertSame(['foo' => 'bar'], $formatted);
    }

    /**
     * @return void
     */
    public function testFormatMergesDefaultAndStoreConfigurationsWhenDefaultKeyExistsAndStoreKeyExists(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter(
            $this->getMockStoreFacadeDefaultStore(),
            $this->getUtilEncodingMock(),
        );

        // Act
        $formatted = $jsonToArrayConfigFormatter->format('{"default": {"foo": "bar", "some": "value"}, "' . static::STORE_NAME . '": {"foo": "bar_' . static::STORE_NAME . '"}}');

        // Assert
        $this->assertSame(['foo' => sprintf('bar_%s', static::STORE_NAME), 'some' => 'value'], $formatted);
    }

    /**
     * @return void
     */
    public function testFormatReturnsDefaultConfigurationWhenDefaultKeyExistsAndStoreKeyDoesNotExist(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter(
            $this->getMockStoreFacadeDefaultStore(),
            $this->getUtilEncodingMock(),
        );

        // Act
        $formatted = $jsonToArrayConfigFormatter->format('{"default": {"foo": "bar"}}');

        // Assert
        $this->assertSame(['foo' => 'bar'], $formatted);
    }

    /**
     * @return void
     */
    public function testFormatReturnsStoreConfigruationWhenDefaultKeyDoesNotExistAndStoreKeyExists(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter(
            $this->getMockStoreFacadeDefaultStore(),
            $this->getUtilEncodingMock(),
        );

        // Act
        $formatted = $jsonToArrayConfigFormatter->format('{"' . static::STORE_NAME . '": {"foo": "bar_' . static::STORE_NAME . '"}}');

        // Assert
        $this->assertSame(['foo' => sprintf('bar_%s', static::STORE_NAME)], $formatted);
    }

    /**
     * @return void
     */
    public function testFormatThrowsExceptionWhenStringCanNotBeConvertedToArray(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter(
            $this->getMockStoreFacadeDefaultStore(),
            $this->getUtilEncodingMock(),
        );

        // Expect
        $this->expectException(ConfigDecodingFailedException::class);

        // Act
        $jsonToArrayConfigFormatter->format('"foo": "bar"');
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Dependency\MessageBrokerAwsToStoreFacadeInterface
     */
    protected function getMockStoreFacadeDefaultStore(): MessageBrokerAwsToStoreFacadeInterface
    {
        $storeFacadeMock = $this->createMock(MessageBrokerAwsToStoreFacadeInterface::class);
        $storeFacadeMock
            ->expects($this->any())
            ->method('getCurrentStore')
            ->willReturn(
                (new StoreTransfer())->setName(static::STORE_NAME),
            );

        return $storeFacadeMock;
    }

    /**
     * @return \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingMock(): MessageBrokerAwsToUtilEncodingServiceInterface
    {
        $utilEncodingService = $this->createMock(MessageBrokerAwsToUtilEncodingServiceInterface::class);

        $utilEncodingService->method('decodeJson')->willReturnCallback(function ($jsonValue, $assoc, $depth = null, $options = null) {
            return json_decode($jsonValue, $assoc);
        });

        return $utilEncodingService;
    }
}
