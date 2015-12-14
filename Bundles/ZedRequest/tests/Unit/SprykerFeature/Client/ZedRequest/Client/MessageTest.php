<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Client\ZedRequest\Client;

use SprykerFeature\Shared\ZedRequest\Client\Message;

/**
 * @group Communication
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
