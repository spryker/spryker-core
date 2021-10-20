<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ZedRequest\Logger;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group ZedRequest
 * @group Logger
 * @group ZedRequestInMemoryLoggerTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Shared\ZedRequest\ZedRequestSharedTester $tester
 */
class ZedRequestInMemoryLoggerTest extends Unit
{
    /**
     * @dataProvider canLogCallsDataProvider
     *
     * @param array $callData
     * @param array $expectedResult
     *
     * @return void
     */
    public function testCanLogCalls(array $callData, array $expectedResult): void
    {
        // Arrange
        $zedRequestInMemoryLogger = $this->tester->createZedRequestInMemoryLogger();

        // Act
        foreach ($callData as $singleCallData) {
            $zedRequestInMemoryLogger->log(
                $singleCallData['destination'],
                $singleCallData['payload'],
                $singleCallData['result']
            );
        }

        // Assert
        $this->assertSame($expectedResult, $zedRequestInMemoryLogger->getLogs());
    }

    /**
     * @return array
     */
    public function canLogCallsDataProvider(): array
    {
        $payloadMessageTransfer = (new MessageTransfer())->setValue('value');
        $anotherPayloadMessageTransfer = (new MessageTransfer())->setValue('another value');
        $responseMessageTransfer = (new MessageTransfer())->setType('success');
        $anotherResponseMessageTransfer = (new MessageTransfer())->setType('error');

        return [
            'first call' => [
                [
                    [
                        'destination' => 'localhost',
                        'payload' => $payloadMessageTransfer->toArray(),
                        'result' => $responseMessageTransfer->toArray(),
                    ],
                ],
                [
                    [
                        'destination' => 'localhost',
                        'payload' => json_encode($payloadMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                        'result' => json_encode($responseMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                    ],
                ],
            ],
            'another calls' => [
                [
                    [
                        'destination' => 'localhost',
                        'payload' => $anotherPayloadMessageTransfer->toArray(),
                        'result' => $anotherResponseMessageTransfer->toArray(),
                    ],
                ],
                [
                    [
                        'destination' => 'localhost',
                        'payload' => json_encode($payloadMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                        'result' => json_encode($responseMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                    ],
                    [
                        'destination' => 'localhost',
                        'payload' => json_encode($anotherPayloadMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                        'result' => json_encode($anotherResponseMessageTransfer->toArray(), JSON_PRETTY_PRINT),
                    ],
                ],
            ],
        ];
    }
}
