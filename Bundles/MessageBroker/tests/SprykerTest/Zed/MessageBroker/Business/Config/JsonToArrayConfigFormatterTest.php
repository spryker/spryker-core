<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Business\Config;

use Codeception\Test\Unit;
use Spryker\Zed\MessageBroker\Business\Config\JsonToArrayConfigFormatter;
use Spryker\Zed\MessageBroker\Business\Exception\ConfigDecodingFailedException;
use Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Business
 * @group Config
 * @group JsonToArrayConfigFormatterTest
 * Add your own group annotations below this line
 */
class JsonToArrayConfigFormatterTest extends Unit
{
    /**
     * @return void
     */
    public function testFormatFormatsJsonStringToArray(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter($this->getUtilEncodingMock());

        // Act
        $formatted = $jsonToArrayConfigFormatter->format('{"foo": "bar"}');

        // Assert
        $this->assertSame(['foo' => 'bar'], $formatted);
    }

    /**
     * @return void
     */
    public function testFormatThrowsExceptionWhenStringCanNotBeConvertedToArray(): void
    {
        // Arrange
        $jsonToArrayConfigFormatter = new JsonToArrayConfigFormatter($this->getUtilEncodingMock());

        // Expect
        $this->expectException(ConfigDecodingFailedException::class);

        // Act
        $jsonToArrayConfigFormatter->format('"foo": "bar"');
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingMock(): MessageBrokerToUtilEncodingServiceInterface
    {
        $utilEncodingService = $this->createMock(MessageBrokerToUtilEncodingServiceInterface::class);

        $utilEncodingService->method('decodeJson')->willReturnCallback(function ($jsonValue, $assoc, $depth = null, $options = null) {
            return json_decode($jsonValue, $assoc);
        });

        return $utilEncodingService;
    }
}
