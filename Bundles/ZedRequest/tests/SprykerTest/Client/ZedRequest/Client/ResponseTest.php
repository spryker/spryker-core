<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ZedRequest\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ZedRequest\Client\Response;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Client\Exception\TransferNotFoundException;
use SprykerTest\Client\ZedRequest\Client\Fixture\TestTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group ResponseTest
 * Add your own group annotations below this line
 */
class ResponseTest extends Unit
{
    const MESSAGE_SUCCESS = 'success';
    const MESSAGE_INFO = 'info';
    const MESSAGE_ERROR = 'error';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Client\ZedRequest\Client\Response
     */
    protected function createFullResponse(TransferInterface $transfer)
    {
        $response = new Response();

        $response->setSuccess(false);

        $response->addSuccessMessages([$this->getMessageTransfer(static::MESSAGE_SUCCESS)]);
        $response->addInfoMessages([$this->getMessageTransfer(static::MESSAGE_INFO)]);
        $response->addErrorMessages([$this->getMessageTransfer(static::MESSAGE_ERROR)]);
        $response->setTransfer($transfer);

        return $response;
    }

    /**
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getMessageTransfer($value)
    {
        $errorMessage = new MessageTransfer();
        $errorMessage->setValue($value)
            ->setParameters([]);

        return $errorMessage;
    }

    /**
     * @return void
     */
    public function testDefaultSuccessIsTrue()
    {
        $response = new Response();
        $this->assertEquals(true, $response->isSuccess());
    }

    /**
     * @return void
     */
    public function testDefaultTransferThrowsException()
    {
        $response = new Response();

        $this->expectException(TransferNotFoundException::class);

        $response->getTransfer();
    }

    /**
     * @return void
     */
    public function testGetterAndSetters()
    {
        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $response = $this->createFullResponse($transfer);

        $this->assertEquals(false, $response->isSuccess());
        $this->assertEquals([$this->getMessageTransfer(self::MESSAGE_ERROR)], $response->getErrorMessages());
        $this->assertEquals([$this->getMessageTransfer(self::MESSAGE_INFO)], $response->getInfoMessages());
        $this->assertEquals([$this->getMessageTransfer(self::MESSAGE_SUCCESS)], $response->getSuccessMessages());
        $this->assertEquals($transfer, $response->getTransfer());
        $this->assertNotSame($transfer, $response->getTransfer());
        $this->assertNotSame($response->getTransfer(), $response->getTransfer());
    }

    /**
     * @return void
     */
    public function testToArrayAndFromArray()
    {
        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $response = $this->createFullResponse($transfer);

        $array = $response->toArray();
        $this->assertInternalType('array', $array, 'toArray does not return array');

        $newResponse = new Response($array);

        $this->assertEquals($response, $newResponse);
        $this->assertNotSame($response, $newResponse);
    }

    /**
     * @return void
     */
    public function testHasMethods()
    {
        $response = new Response();

        $response->addErrorMessage($this->getMessageTransfer(self::MESSAGE_ERROR));
        $response->addInfoMessage($this->getMessageTransfer(self::MESSAGE_INFO));

        $this->assertTrue($response->hasErrorMessage(self::MESSAGE_ERROR), 'Error message could not be found in response.');
        $this->assertFalse($response->hasErrorMessage(self::MESSAGE_INFO), 'Info message was not expected as error in response.');

        $this->assertTrue($response->hasInfoMessage(self::MESSAGE_INFO), 'Info message could not be found in response.');
        $this->assertFalse($response->hasInfoMessage(self::MESSAGE_ERROR), 'Error message was not expected as info in response.');
    }
}
