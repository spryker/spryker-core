<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\ZedRequest\Client;

use PHPUnit_Framework_TestCase;
use Spryker\Client\ZedRequest\Client\Request;
use Spryker\Shared\Transfer\TransferInterface;
use Unit\Spryker\Client\ZedRequest\Client\Fixture\TestTransfer;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group RequestTest
 */
class RequestTest extends PHPUnit_Framework_TestCase
{

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transfer
     * @param \Spryker\Shared\Transfer\TransferInterface|null $metaTransfer1
     * @param \Spryker\Shared\Transfer\TransferInterface|null $metaTransfer2
     *
     * @return \Spryker\Client\ZedRequest\Client\Request
     */
    protected function createFullRequest(
        TransferInterface $transfer,
        TransferInterface $metaTransfer1 = null,
        TransferInterface $metaTransfer2 = null
    ) {
        $request = new Request();

        $request->setPassword('password');
        $request->setHost('host');
        $request->setSessionId('sessionId');
        $request->setTime(1234567);
        $request->setTransfer($transfer);
        $request->setUsername('username');

        if ($metaTransfer1) {
            $request->addMetaTransfer('meta1', $metaTransfer1);
        }

        if ($metaTransfer2) {
            $request->addMetaTransfer('meta2', $metaTransfer2);
        }

        return $request;
    }

    /**
     * @return void
     */
    public function testDefaultTransferIsNull()
    {
        $response = new Request();
        $this->assertEquals(null, $response->getTransfer());
        $this->assertEquals(null, $response->getMetaTransfer('asd'));
    }

    /**
     * @return void
     */
    public function testGetterAndSetters()
    {
        $transfer = new TestTransfer();
        $transfer->setFoo('bar');
        $request = $this->createFullRequest($transfer);

        $this->assertEquals('password', $request->getPassword());
        $this->assertEquals('host', $request->getHost());
        $this->assertEquals('sessionId', $request->getSessionId());
        $this->assertEquals(1234567, $request->getTime());
        $this->assertEquals($transfer, $request->getTransfer());
        $this->assertNotSame($transfer, $request->getTransfer());
        $this->assertNotSame($request->getTransfer(), $request->getTransfer());
        $this->assertEquals('username', $request->getUsername());
    }

    /**
     * @return void
     */
    public function testMetaTransfersAreStoredCorrectly()
    {
        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $metaTransfer1 = new TestTransfer();
        $metaTransfer1->setFoo('bar');

        $metaTransfer2 = new TestTransfer();
        $metaTransfer2->setFoo('baz');

        $request = $this->createFullRequest($transfer, $metaTransfer1, $metaTransfer2);

        $this->assertEquals($metaTransfer1, $request->getMetaTransfer('meta1'));
        $this->assertNotSame($metaTransfer1, $request->getMetaTransfer('meta1'));
        $this->assertEquals($metaTransfer2, $request->getMetaTransfer('meta2'));
        $this->assertNotSame($metaTransfer2, $request->getMetaTransfer('meta2'));
    }

    /**
     * @return void
     */
    public function testToArrayAndFromArray()
    {
        $transfer = new TestTransfer();
        $transfer->setFoo('foo');

        $metaTransfer1 = new TestTransfer();
        $metaTransfer1->setFoo('bar');

        $metaTransfer2 = new TestTransfer();
        $metaTransfer2->setFoo('baz');

        $request = $this->createFullRequest($transfer, $metaTransfer1, $metaTransfer2);

        $array = $request->toArray();

        $this->assertTrue(is_array($array), 'toArray does not return array');

        $newRequest = new Request($array);

        $this->assertEquals($request, $newRequest);
        $this->assertNotSame($request, $newRequest);
    }

}
