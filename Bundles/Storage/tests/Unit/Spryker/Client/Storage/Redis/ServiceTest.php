<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Storage\Redis;

use Codeception\TestCase\Test;
use Predis\ClientInterface;
use Spryker\Client\Storage\Redis\Service;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Storage
 * @group Redis
 * @group ServiceTest
 */
class ServiceTest extends Test
{

    /**
     * @var \Spryker\Client\Storage\Redis\Service
     */
    protected $redisService;

    /**
     * @var \Predis\ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $clientMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->clientMock = $this->getMock(
            ClientInterface::class,
            [
                'keys',
                'getProfile',
                'getOptions',
                'connect',
                'disconnect',
                'createCommand',
                'executeCommand',
                'getConnection',
                '__call',
            ]
        );

        $this->redisService = new Service(
            $this->clientMock
        );
    }

    /**
     * @return void
     */
    public function testGetAllKeysTriggersRightCommand()
    {
        $this->clientMock->expects($this->once())->method('keys')->with($this->equalTo('kv:*'));

        $this->redisService->getAllKeys();
    }

    /**
     * @return void
     */
    public function testGetKeysPassesPatternCorrectly()
    {
        $this->clientMock->expects($this->once())->method('keys')->with($this->equalTo('kv:aPattern*'));

        $this->redisService->getKeys('aPattern*');
    }

    /**
     * @return void
     */
    public function testGetMultiWithEmptyKeys()
    {
        $requestedKeys = [];

        $this->assertSame($requestedKeys, $this->redisService->getMulti($requestedKeys));
    }

}
