<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\ZedRequest\Client;

use Spryker\Shared\ZedRequest\Client\Message;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group ZedRequest
 * @group Client
 * @group MessageTest
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetterAndSetters()
    {
        $message = new Message();

        $message->setData(['test' => 'test']);
        $message->setMessage('message');

        $this->assertEquals('message', $message->getMessage());
        $this->assertEquals(['test' => 'test'], $message->getData());

        $this->assertEquals(['message' => 'message', 'data' => ['test' => 'test']], $message->toArray());
    }

}
